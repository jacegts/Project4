<?php 
//    session_start();
	$app->get('/', function () {
            require_once realpath(__DIR__.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.'LoginForm.html');
	});

        
        $app->get('/profile/:name', function ($name) {
            echo $name;
            require_once realpath(__DIR__.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.'Profile.html');
            
	});

//        $app->post('/profileCheck', function() use ($app)
//        {
//            $user=$app->request()->params('name');
//            $pass =$app->request()->params('password');
//            
//            //fix this
//            $SQLReg = new \Common\Authentication\SQLiteReg($user,$pass);
//            //var_dump($SQLReg->getProfile());
////            echo json_encode($SQLReg->getProfile());
////            $S =  $app->response->setBody(json_encode($SQLReg->getProfile()));
////            echo $S;
//            //echo $app->response->writeBody(json_encode($SQLReg->getProfile()));
////            echo (json_encode($SQLReg->getProfile()));
//            //var_dump($SQLReg->getProfile());
////            $S = $SQLReg->getProfile();
//////            echo $S;
//////            $S = json_encode($SQLReg->getProfile());
////            echo($S); 
////            echo '{"test": "test"}';
////            echo '{"test": "'.$S.'"}';
////            return json_encode($app->response()->body('{"test": "'.$S.'"}'));
//            exit();
//            
//        });

        $app->post('/newuser', function() use ($app)
        {
            $user = $app->request->params('username');
            $pass = $app->request->params('password'); 
            $SQLReg = new \Common\Authentication\SQLiteReg($user,$pass);
            $SQLRes = $SQLReg->registerNewUser();
            if($SQLRes!==1)
            {
                $app->response()->setStatus(401);
                echo $app->response()->getStatus();
                return json_encode($app->response()->header('User failed to create.',401));
            }
            //echo $SQLReg ->registerNewUser();
            if ($SQLRes===1)
            {
                
                $app->response()->setStatus(200);
                echo $app->response()->getStatus();
                return json_encode($app->response()->header('User successfully created.',200));   
            }
        }
        );
        $app->get('/genAuth', function ()
        {
            require_once realpath(__DIR__.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.'genAuth.php');   
        });
        $app->post('/api',function()use($app){
           //echo 'api endpoint'; 
            //$data = $app->request->getBody();
            $user = $app->request->params('username');
            $pass = $app->request->params('password');
            $auth = $app->request->params('authKey');
           $SQLAuth = new \Common\Authentication\SQLiteAuth($user,$pass);
           if($SQLAuth->authenticateA($auth)!==1)
           {  
                $app->response()->setStatus(403);
                //$app->response()->getStatus();
                return json_encode($app->response()->header('Need an authentication key? : localhost:8080/genAuth', 403));
           }
           if($SQLAuth->authenticate()!==1)
           {
                $app->response()->setStatus(401);
                //$app->response()->getStatus();
                return json_encode($app->response()->header('Need to register? : localhost:8080/RegistrationForm', 401));
           }
           if($SQLAuth->authenticate()===1)
           {
                $app->response()->setStatus(200);
                //$app->response()->getStatus();
                $SQLReg = new \Common\Authentication\SQLiteReg($user,$pass);
                $S = $SQLReg->getProfile();
//                var_dump($S);
                $S = json_encode($S);
//                var_dump($S);
                echo $S;
                //return json_encode($app->response()->header('Login successful : localhost:8080/Profile', 200));
           }
        });

	$app->post('/twitter', function(){
            //echo 'post twitter';
            require_once realpath(__DIR__.DIRECTORY_SEPARATOR.'Common'.DIRECTORY_SEPARATOR.'Authentication'.DIRECTORY_SEPARATOR.'TwitterAuth.php');
            //TwitterAuth();
            
        });        

        $app->get('/register', function(){
            require_once realpath(__DIR__.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.'RegistrationForm.html');

            //echo 'get crap';
	});


	$app->run();