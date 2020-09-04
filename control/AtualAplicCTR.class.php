<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/AtualAplicDAO.class.php');
/**
 * Description of AtualAplicativoCTR
 *
 * @author anderson
 */
class AtualAplicCTR {
    //put your code here
    
    private $base = 2;
    
    public function atualAplic($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 1.00){
        
            $atualAplicDAO = new AtualAplicDAO();

            $jsonObj = json_decode($info['dado']);
            $dados = $jsonObj->dados;

            foreach ($dados as $d) {
                $equip = $d->nroEquipAtual;
                $va = $d->versaoAtual;
            }
            $retorno = 'N';
            $v = $atualAplicDAO->verAtual($equip, $this->base);
            if ($v == 0) {
                $atualAplicDAO->insAtual($equip, $va, $this->base);
            } else {
                $result = $atualAplicDAO->retAtual($equip, $this->base);
                foreach ($result as $item) {
                    $vn = $item['VERSAO_NOVA'];
                    $vab = $item['VERSAO_ATUAL'];
                }
                if ($va != $vab) {
                    $atualAplicDAO->updAtualNova($equip, $va, $this->base);
                } else {
                    if ($va != $vn) {
                        $retorno = 'S';
                    } else {
                        if (strcmp($va, $vab) <> 0) {
                            $atualAplicDAO->updAtual($equip, $va, $this->base);
                        }
                    }
                }
            }
            $dthr = $atualAplicDAO->dataHora($this->base);
            if ($retorno == 'S') {
                return $retorno;
            } else {
                return $retorno . "#" . $dthr;
            }
        
        }
        
    }
    
}
