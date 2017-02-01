
<?php

class VotacionController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        if (!Yii::app()->getUser()->hasState('_document')) {
            $this->redirect('index.php?r=site/votacion');
        }
        $document = Yii::app()->user->_document;
        $Criteria = new CDbCriteria();
        $Criteria->condition = "Cedula = $document";
        $idProfile = Yii::app()->user->_idProfile;
        $controllers = Yii::app()->controller->getId();
        $actionsProfile = Consult::model()->getActionsProfile($idProfile, $controllers);
        $actionStatus = new JAction();

        try {
            $actionAjax = $actionStatus->getActions(ucfirst($controllers) . 'Controller', '');
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        
        $actions = array();
        foreach ($actionsProfile as $itemAction) {
            array_push($actions, $itemAction['Description']);
        }
        
        foreach ($actionAjax as $item) {
            $data = strtolower('ajax' . $item);
            array_push($actions, $data);
        }

        $arrayAction = Links::model()->findProfile(ucfirst($controllers), $idProfile);
        $arrayDifferences = $actionStatus->diffActions(ucfirst($controllers) . 'Controller', '', $arrayAction);

        $session = new CHttpSession;
        $session->open();
        $session['differences'] = $arrayDifferences;
               
        if (count($actions) <= 0) {
            return array(
                array('deny',
                    'users' => array('*'),
                ),
            );
        } else {
            return array(
                array('allow',
                    'actions' => $actions,
                    'users' => array('@'),
                ),
                array('deny',
                    'users' => array('*'),
                ),
            );
        }
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionElejirCandidato() {
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl . '/js/votacion/votacion.js', CClientScript::POS_END
        );
        $currentElection = Votacion::model()->getCurrentElection();
        $idZone = Yii::app()->user->_idZone;
        switch (true):
            case $idZone < 8:
                VotacionController::allAburraZone($currentElection);
                break;
            default:
                VotacionController::individualZone($idZone, $currentElection);
                break;
        endswitch;
    }

    protected function allAburraZone($currentElection) {
        $zoneName = Votacion::model()->getAburraZones();
        $quantity = count($zoneName) - 1;
        $count = 0;
        foreach ($zoneName as $itemZone):
            if ($count == $quantity):
                $candidates[$count] = Votacion::model()->getCandidatesZone($itemZone['IdRegion'],'Blanco');
            else:
                $candidates[$count] = Votacion::model()->getCandidatesZone($itemZone['IdRegion']);
            endif;
            $count++;
        endforeach;
        $this->render('_candidatosZona', array(
            'candidatos' => $candidates,
            'nombreZona' => $zoneName, 'eleccion' => $currentElection
        ));
    }

    protected function individualZone($zone, $currentElection) {
        $zoneName = Votacion::model()->getZoneName($zone);
        $count = 0;
        foreach ($zoneName as $itemZone):
            $candidates[$count] = Votacion::model()->getCandidatesZone($itemZone['IdRegion'],'Blanco');
            $count++;
        endforeach;
        $this->render('_candidatosZona', array(
            'candidatos' => $candidates,
            'nombreZona' => $zoneName, 'eleccion' => $currentElection
        ));
    }

    public function actionRegistrarVoto() {
        if(isset($_POST['candidate'])):
            //RECIBIR PARAMETROS POST
            $user = Yii::app()->user->_document;
            //VALIDAR ESTADO USUARIO
            $status = Votacion::model()->getStatus($user);
            //INDEPENDIENTE DE LA RESPUESTA SE TERMINA LA SESION DEL USUARIO
            Yii::app()->user->logout();
            if ($status == 0):
                VotacionController::saveVote($_POST['candidate'],$_POST['zone'],$user);
            else:
                //CANDIDATO CON VOTO REGISTRADO ANTERIORMENTE
                //VALIDAR VOTACION DE FORMA FISICA
                $this->render('_votoAnulado');
            endif;
        else:
            //SE DETECTO UN INTENTO DE FRAUDE POR LO CUAL DEBE INICIAR SESION NUEVAMENTE
            Yii::app()->user->logout();
            $this->redirect("index.php?r=site/votacion");
        endif;
    }
//aca retornar usuario votado
    protected function saveVote($candidate, $zone, $user) {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $model = new Votacion;
        $model->CodCandidato = $candidate;
        $model->CodRegion = $zone;
        $model->CodUsuario = $user;
        $model->Fecha = $date;
        $model->Hora = $time;
        if ($model->save()):
            //SE REGISTRO EL VOTO CORRECTAMENTE
            //CAMBIAR ESTADO DEL USUARIO A 1
            Votacion::model()->chageStatus($user, 1);
            //MOSTRAR MENSAJE DE VOTO REGISTRADO
            $candidato_Nombre = Votacion::model()->getCandidato($candidate);
            $this->render('_votoRegistrado',array('candidatoVotado'=>$candidato_Nombre));
        else:
            //OCURRIO UN ERROR CON LA INFORMACION
            $this->render('_informacionErronea');
        endif;
    }

    public function actionVotoFisico() {
        Yii::app()->clientScript->registerScriptFile(
                Yii::app()->baseUrl . '/js/votacion/votacion.js', CClientScript::POS_END
        );
        $currentElection = Votacion::model()->getCurrentElection();
        $this->render('_VotoFisico', array('eleccion'=>$currentElection));
    }

    public function actionAjaxUserInformation() {
        if (isset($_POST['document'])):
            $userInformation = Votacion::model()->getUserInformation($_POST['document']);
            $this->renderPartial('_tblUsuario', array('userInformation'=>$userInformation));
        endif;
    }

    public function actionAjaxGetList() {
        $data = Votacion::model()->getList($_POST["id"]);
        $this->renderPartial('_modalUserList', array('data'=>$data));
    }

    public function actionAjaxUnableUser() {
        if (isset($_POST['user'])):
            $user = $_POST['user'];
            Votacion::model()->chageStatus($user, 2);
            $message = "Usuario : " . Yii::app()->user->_document . " Sufragante : " . $user;
            Yii::log($message,'fisicVotes','status');
            $userInformation = Votacion::model()->getUserInformation($user);
            $this->renderPartial('_tblUsuario', array('userInformation'=>$userInformation));
        endif;
    }

    public function actionReporteVotacion() {
        Yii::app()->clientScript->registerScriptFile(
                Yii::app()->baseUrl . '/js/votacion/votacion.js', CClientScript::POS_END
        );
        $currentElection = Votacion::model()->getCurrentElection();
        $regions = Votacion::model()->getAllRegions($currentElection['IdEleccion']);
        $this->render('_ReporteVotacion', array('eleccion'=>$currentElection,'zonas'=>$regions));
    }

    public function actionAjaxGenerateStatistical() {
        if (isset($_POST['zonas'])):
            $zonas = $_POST['zonas'];
            $count = 0;
            foreach ($zonas as $zona):
                $statistical[$count] = Votacion::model()->getCandidatesZone($zona);
                $count++;
            endforeach;
            $this->renderPartial('_Statistical', array('statistical' => $statistical));
        endif;
    }

    public function actionReporteGeneral() {
        if(isset($_GET['zone'])):
            VotacionController::customsStatistics($_GET['zone']);
        else:
            VotacionController::generalStatistics();
        endif;
    }

    protected function customsStatistics($zone) {
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl . '/js/votacion/votacion.js', CClientScript::POS_END
        );
        $currentElection = Votacion::model()->getCurrentElection();
        $zoneName = Votacion::model()->getZoneName($zone);
        $allVoters = Votacion::model()->getAllVoters($zone);
        $voters = Votacion::model()->getVoters($zone);
        $electronicVotes = Votacion::model()->getElectronicVoters($zone);
        $inactiveVotes = Votacion::model()->getInactiveVoters($zone);
        $whiteVotes = Votacion::model()->getWhiteVoters($zone);
        $fisicVotes = Votacion::model()->getFisicVoters($zone);
        $nullVotes = Votacion::model()->getNullVoters($zone);
        $customsStatistics = Votacion::model()->getCustomsStatistics($zone);
        $this->render('_ReporteEspecifico', array(
            'eleccion'=>$currentElection,'estadistica'=>$customsStatistics,
            'nombreZona'=>$zoneName,'totalUsuarios'=>$allVoters['total'],
            'totalVotos'=>$voters['sufragantes'],'votosElectronicos'=>$electronicVotes['electronico'],
            'votosInactivos'=>$inactiveVotes['inactivo'],'votosBlanco'=>$whiteVotes['blanco'],
            'votosNulos'=>$nullVotes['cantidad'],'votosFisicos'=>$fisicVotes['cantidad']
        ));
    }

    protected function generalStatistics() {
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl . '/js/votacion/votacion.js', CClientScript::POS_END
        );
        $currentElection = Votacion::model()->getCurrentElection();
        $allVoters = Votacion::model()->getAllVoters();
        $voters = Votacion::model()->getVoters();
        $electronicVotes = Votacion::model()->getElectronicVoters();
        $inactiveVotes = Votacion::model()->getInactiveVoters();
        $whiteVotes = Votacion::model()->getWhiteVoters();
        $fisicVotes = Votacion::model()->getFisicVoters();
        $nullVotes = Votacion::model()->getNullVoters();
        $regions = Votacion::model()->getAllRegions($currentElection['IdEleccion']);
        $detailNotVoting = VotacionController::detailNotVoting($regions);
        $detailVoting = VotacionController::detailVoting($regions);
        $this->render('_ReporteGeneral', array(
            'eleccion'=>$currentElection,'totalUsuarios'=>$allVoters['total'],
            'totalVotos'=>$voters['sufragantes'],'votosElectronicos'=>$electronicVotes['electronico'],
            'votosInactivos'=>$inactiveVotes['inactivo'],'votosBlanco'=>$whiteVotes['blanco'],
            'detalleNoVotos'=>$detailNotVoting,'detalleVotos'=>$detailVoting,
            'regiones'=>$regions,'votosNulos'=>$nullVotes['cantidad'],'votosFisicos'=>$fisicVotes['cantidad']
        ));
    }

    protected function detailNotVoting($regions) {
        $count = 0;
        foreach ($regions as $region):
            $detailNotVoting[$count] = Votacion::model()->getDetailNotVoting($region['IdRegion']);
            $count++;
        endforeach;
        return $detailNotVoting;
    }

    protected function detailVoting($regions) {
        $count = 0;
        foreach ($regions as $region):
            $detailVoting[$count] = Votacion::model()->getVoters($region['IdRegion']);
            $count++;
        endforeach;
        return $detailVoting;
    }

    public function actionAjaxGraficos() {
        $allVoters = Votacion::model()->getAllVoters();
        $voters = Votacion::model()->getVoters();
        $this->renderPartial('imgGraph',array(
            'totalUsuarios'=>$allVoters['total'], 'totalVotos'=>$voters['sufragantes']
        ));
    }

    public function actionRegistrarVotacionFisica() {
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl . '/js/votacion/votacion.js', CClientScript::POS_END
        );
        $currentElection = Votacion::model()->getCurrentElection();
        VotacionController::allZones($currentElection);
    }

    protected function allZones($currentElection) {
        $allZones = Votacion::model()->getAllRegions($currentElection['IdEleccion']);
        $count = 0;
        foreach ($allZones as $itemZone):
            if (VotacionController::isAvailable($itemZone['IdRegion'])):
                $zone = Votacion::model()->getZoneName($itemZone['IdRegion']);
                $zones[$count] = $zone[0];
                $candidates[$count] = Votacion::model()->getCandidatesZone($itemZone['IdRegion'],'Blanco','SI');
                $count++;
            endif;
        endforeach;
        $this->render('_registroVotoFisico', array(
            'candidatos' => $candidates,
            'nombreZona' => $zones, 'eleccion' => $currentElection
        ));
    }

    protected function isAvailable($zone) {
        $user = Yii::app()->user->_document;
        return Votacion::model()->validateZoneByDelegate($zone, $user);
    }

    public function actionRegistrarVotoFisico() {
        if(isset($_POST['candidates'])):
            //RECIBIR PARAMETROS POST
            $arrayCandidates = explode(",", $_POST['candidates'][0]);
            $arrayQuantities = explode(",", $_POST['quantities'][0]);
            $zone = $_POST['zone'];
            if (VotacionController::isAvailable($zone)):
                $user = Yii::app()->user->_document;
                VotacionController::saveFisicVote($arrayCandidates, $arrayQuantities, $zone, $user);
            else:
                //ZONA CON VOTOS REGISTRADOS ANTERIORMENTE X DELEGADO
                $zoneName = Votacion::model()->getZoneName($zone);
                $this->render('_votosFisicosNoRegistrados', array('nombreZona'=>$zoneName[0]['Nombre']));
            endif;
        else:
            //SE DETECTO UN INTENTO DE FRAUDE POR LO CUAL DEBE INICIAR SESION NUEVAMENTE
            Yii::app()->user->logout();
            $this->redirect("index.php?r=site/admin");
        endif;
    }

    protected function saveFisicVote($candidates, $quantities, $zone, $user) {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        for ($i=0; $i<count($candidates); $i++):
            if ($candidates[$i] == 0):
                VotacionController::saveNullVotes($quantities[$i], $zone, $user);
            else:
                $model = new VotosFisicos();
                $model->unsetAttributes();
                $model->CodigoCandidato = $candidates[$i];
                $model->CodigoRegion = $zone;
                $model->CantidadVotos = $quantities[$i];
                $model->CedulaDelegado = $user;
                $model->Fecha = $date;
                $model->Hora = $time;
            endif;
            if (!$model->save()):
                //OCURRIO UN ERROR CON LA INFORMACION
                $this->render('_informacionErronea');
            endif;
        endfor;
        //SE REGISTRO EL VOTO CORRECTAMENTE
        $this->render('_redirect');
    }

    protected function saveNullVotes($quantity, $zone, $user) {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $model = new VotosNulos();
        $model->unsetAttributes();
        $model->Usuario = $user;
        $model->Cantidad = $quantity;
        $model->CodRegion = $zone;
        $model->Fecha = $date;
        $model->Hora = $time;
        if (!$model->save()):
            //OCURRIO UN ERROR CON LA INFORMACION
            $this->render('_informacionErronea');
        endif;
    }
}
