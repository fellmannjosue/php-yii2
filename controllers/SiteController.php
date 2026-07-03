<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\ContactForm;
use app\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\base\Security;
use yii\mail\MailerInterface;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

class SiteController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly MailerInterface $mailer,
        private readonly Security $security,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'transparent' => true,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    /** Datos del portal (Yii2, framework real). */
    private function portalData(): array
    {
        $FW = [
            'name'    => 'Yii2',
            'tagline' => 'Robusto, con buena generación de código (Gii) y rendimiento sólido. Complejidad media. Popular para apps rápidas.',
            'accent'  => '#4CA64C',
            'accent2' => '#3B8C3B',
            'site'    => 'https://www.yiiframework.com',
            'kind'    => 'Framework real',
        ];
        $functions = [
            ['icon' => '🧭', 'title' => 'Enrutamiento', 'live' => true,
             'desc' => 'Controladores y acciones (esta página es site/index y /api es site/api).',
             'code' => "// controllers/SiteController.php\npublic function actionSaludo(\$nombre) {\n    return \"Hola \$nombre\";\n}"],
            ['icon' => '🎨', 'title' => 'Vistas', 'live' => true,
             'desc' => 'render() de vistas PHP. Esta página se renderiza así.',
             'code' => "return \$this->render('portal', \$datos);"],
            ['icon' => '🔌', 'title' => 'API JSON', 'live' => true,
             'desc' => 'Response en formato JSON. Aquí funciona de verdad.',
             'code' => "\$this->response->format = Response::FORMAT_JSON;\nreturn ['mensaje' => 'Hola Mundo'];",
             'link' => 'index.php?r=site/api', 'linkText' => 'Probar el endpoint JSON (site/api) →'],
            ['icon' => '✅', 'title' => 'Validación', 'live' => true,
             'desc' => 'Reglas en modelos (rules). Aquí se valida un correo real.',
             'code' => "public function rules() {\n    return [['email', 'email']];\n}",
             'form' => true],
            ['icon' => '🗄️', 'title' => 'ActiveRecord', 'live' => false,
             'desc' => 'ORM ActiveRecord y generador de código Gii.',
             'code' => "\$usuarios = User::find()\n    ->where(['activo' => 1])->all();"],
        ];
        $compare = [
            ['Symfony','Enterprise, modular','Alta','Alto (corporativo)','Proyectos grandes'],
            ['Laravel','Full-stack, todo incluido','Media-alta','Muy alto (#1)','Apps modernas'],
            ['Laminas','Modular corporativo','Alta','Bajo (en declive)','Legacy empresarial'],
            ['Yii2','Full-stack + Gii','Media','Medio (regional)','Apps rápidas'],
            ['CakePHP','Convención sobre config.','Media','Modesto/estable','CRUD clásico'],
            ['Phalcon','Extensión C, rapidísimo','Media-alta (setup)','Nicho','Rendimiento extremo'],
            ['CodeIgniter','Ligero, poca magia','Baja','Medio (bajando)','Proyectos pequeños'],
            ['Slim','Micro-framework','Baja','Nicho (por diseño)','APIs pequeñas'],
            ['Lumen','Micro-Laravel','Baja','En declive','Microservicios (obsoleto)'],
        ];
        return compact('FW', 'functions', 'compare');
    }

    public function actionIndex(): string
    {
        $d = $this->portalData();
        $formResult = null;
        $email = Yii::$app->request->get('email');
        if ($email !== null) {
            $email = trim($email);
            $ok = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
            $formResult = $ok
                ? ['ok' => true,  'msg' => "✓ '$email' es un correo válido."]
                : ['ok' => false, 'msg' => "✗ '$email' no es un correo válido."];
        }
        return $this->renderPartial('portal', $d + ['formResult' => $formResult]);
    }

    public function actionApi(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $FW = $this->portalData()['FW'];
        return [
            'framework' => $FW['name'],
            'mensaje'   => 'Hola Mundo desde un endpoint JSON',
            'hora'      => date('c'),
            'ok'        => true,
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm($this->security);

        if ($model->load($this->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact(): Response|string
    {
        $model = new ContactForm();

        $contact = $model->load($this->request->post()) && $model->contact(
            $this->mailer,
            Yii::$app->params['adminEmail'],
            Yii::$app->params['senderEmail'],
            Yii::$app->params['senderName'],
        );

        if ($contact) {
            Yii::$app->session->setFlash(
                'success',
                'Thank you for contacting us. We will respond to you as soon as possible.',
            );

            return $this->refresh();
        }

        return $this->render('contact', ['model' => $model]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout(): string
    {
        return $this->render('about');
    }
}
