<?php 

namespace Desafioaba\Services;

class Validation
{
    public function required($txt, $campo)
    {
        $txt = trim($txt);

        if($txt){
            return $txt;
        }

        http_response_code(500);
        die(json_encode(['message' => "$campo é um campo obrigatório!"]));
    }

    public function min_max($txt, $min, $max, $campo)
    {
        $txt = $this->required($txt, $campo);

        if(strlen($txt) < $min){
            http_response_code(500);
            die(json_encode(['message' => "O campo $campo tem o mínimo de $min caracteres!"]));
        } else if (strlen($txt) > $max){
            http_response_code(500);
            die(json_encode(['message' => "O campo $campo tem o máximo de $max caracteres!"]));
        }

        return $txt;
    }

    public function birthdate($txt)
    {
        $txt = $this->min_max($txt, 10, 10, 'Data de Nascimento');

        $months_31 = [1, 3, 5, 7, 8, 10, 12];
        $last_day = '';
        list($day, $month, $year) = explode("/", $txt);

        if($month >= 1 && $month <= 12 && $year > 0 && $year < date('Y') && $year > '1910'){
            if(in_array($month, $months_31)){
                $last_day = 31;
            } else if($month == 2){
                if($year % 4 == 0 && ($year % 100 != 0 || $year % 400 == 0)){
                    $last_day = 29;
                } else {
                    $last_day = 28;
                }
            } else {
                $last_day = 30;
            }
        }

        if($day > $last_day){
            http_response_code(500);
            die(json_encode(['message' => 'Data informada inválida!']));
        }

        $txt = str_replace("/", "-", $txt);
        $txt = date('Y-m-d', strtotime($txt));

        return $txt;
    }

    public function num($txt, $campo)
    {
        $txt = $this->required($txt, $campo);

        if(!is_numeric($txt)){
            http_response_code(500);
            die(json_encode(['message' => "O campo $campo é númerico!"]));
        }

        return $txt;
    }
}