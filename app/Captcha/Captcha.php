<?php
/*
##### CAPTCHA
##### tableau des nombres -> $listNb
##### 2 hashs cutsomizable -> $hash1, $hash2
##### Nomber de caractère des chiffres cryptés -> $nbCryptCarat
*/

namespace App\Captcha;


class Captcha{

    private $listNb = array(
        'z&eacute;ro',
        'un',
        'deux',
        'trois',
        'quatre',
        'cinq',
        'six',
        'sept',
        'huit',
        'neuf'
    );

    private $hash1          = 't+3F8Jz:';
    private $hash2          = 'j49:v7~vARZ>';
    private $nbCryptCaract  = 44;
    private $destNO         = 'Erreur ! La réponse est incorrect';
    private $messageTokenOff = 'Votre session a expiré, merci de rafraîchir votre navigateur';


    public function __construct(){
        $button         = $this->listNb;
    }


    /*
    * Show shuffle numbers
    */
    public function nbShuffle($min = 0, $max = 9){
        $numbersShow = range($min, $max);
        shuffle($numbersShow);
        foreach ($numbersShow as $numberShow) {
            $listShow[] = $numberShow;
        }
        return $listShow;
    }

    /*
    * Question
    */
    public function question($nbChiffre = 2){
        $operation = '';
        $rand_keys = array_rand($this->listNb, $nbChiffre);
        for($n = 0; $n < $nbChiffre; $n++){
            $ope        = array();
            $ope[]      = $rand_keys[$n];
            $ope[]      = $this->listNb[$rand_keys[$n]];
            $operation  .= $ope[mt_rand(0, 1)];
            if($n != $nbChiffre-1) $operation .= " + ";
        }
        return $operation;
    }


    /*
    * Réponse
    */
    // Ajout de chaque nombre de la question
    private function reponse($question = '', $reponse = ''){
        $operation  = '';
        $quest      = explode(" + ", $question);
        foreach($quest as $q){
            if(array_search($q, $this->listNb) == true){
                $operation += array_search($q, $this->listNb);
            } else{
                $operation += $q;
            }
        }
        return $this->calcul_reponse($operation, $reponse);
    }
    // Retour de la réponse crypté avec plusieurs chiffres
    private function calcul_reponse($operation, $reponse){
        $rep = '';
        if(strlen($reponse) > $this->nbCryptCaract){
            for($i = 1; $i <= strlen($reponse); $i = $i + $this->nbCryptCaract){
                $rep .= $this->decrypt(substr($reponse, $i - 1, ($i - 1) + $this->nbCryptCaract));
            }
            return $this->reponse_result($this->crypt($operation), $this->crypt($rep));
        } else{
            $rep = $reponse;
            return $this->reponse_result($this->crypt($operation), $rep);
        }
    }
    // Validation de la réponse par rapport à la question
    private function reponse_result($question, $reponse){
        if($question == $reponse){
            return true;
        } else{
            return $this->destNO;
        }
    }

    // Fonction ouverte qui renvoi vers des fonctions privées et vérifie le token (présence de l'ID token et vérification de sa durée)
    public function get_reponse($question = '', $reponse = '', $token_time, $token_id){
        if(isset($token_id) && $token_time >= time() - (2*60)){
            return $this->reponse($question, $reponse);
        } else{
            return $this->messageTokenOff;
        }
    }


    /*
    * Sécurité
    */
    // Cryptage du nombre
    public function crypt($number){
        $qEncoded       = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $this->hash1 ), $number, MCRYPT_MODE_CBC, md5( md5( $this->hash2 ) ) ) );
        return $qEncoded;
    }
    // Décrypt le nombre
    private function decrypt($number){
        $qDeccode_Full  = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $this->hash1 ), base64_decode( $number ), MCRYPT_MODE_CBC, md5( md5( $this->hash2 ) ) ), "\0");
        return $qDeccode_Full;
    }


    /*
    * Envoie validation du captcha
    */
    public function verify(){
        return $this->get_reponse($_POST['question'], $_POST['answer_value'], $_POST['token_time'], $_POST['token']);
    }




}
