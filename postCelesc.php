<?php


$header_position = 0;
$my_array = array();

var_dump($_FILES);

$f = fopen('php://input','r');

$position = 0;

fseek($f, ftell($f));
while (($line = stream_get_line($f, 1024 * 1024, "\n")) !== false) {
    if ($header_position > 0){   //ignora o header  
        if (substr($line, $position, 1) == "2")
        {
            $celesc =  CreateRecordCelescCadastro($line, $position);
            $position = 0;
            $my_array[] = $celesc;
        }
        elseif (substr($line, $position, 1) == "6")
        {
            $celesc =  CreateRecordCelescLancamento($line, $position);
            $position = 0;
            $my_array[] = $celesc;
        }
    }

 
    $header_position += 1;
 }


 function TranslateInformative($texto)
 {
     switch($texto)
     {
        case "81":
            return $texto . "-Faturado";            
        case "82":
            return $texto . "-Arrecadado (Fatura Paga)";            
        case "86":
            return $texto . "-Alteração de vencimento";            
        case "90":
            return $texto . "-Parcela cancelada";            
        case "91":
            return $texto . "-Cancelamento da arrecadação";            
        default:
            return $texto;
     }
 }

 function TranslateCoberturaOcorrencia($texto)
 {
    switch($texto)
     {
        case "00":
            return $texto . "-Ativo"; 
        case "01":
                    return $texto . "-Dificuldade financeira"; 
        case "02":
                    return $texto . "-Já possui seguro"; 
        case "03":
                    return $texto . "-Não quer mais o seguro"; 
        case "19":
                    return $texto . "-Outros (*)"; 
        case "21":
                    return $texto . "-Mudança de Classe / Classe da UC não permitida"; 
        case "22":
                    return $texto . "-Troca de titularidade"; 
        case "23":
                    return $texto . "-Grupo de tensão da UC diferente de A ou B"; 
        case "24":
                    return $texto . "-Localização da UC não é Urbana ou Rural"; 
        case "25":
                    return $texto . "-Localidade sem convênio"; 
        case "26":
                    return $texto . "-Convênio encerrado"; 
        case "27":
                    return $texto . "-Convênio de localidade encerrado"; 
        case "28":
                    return $texto . "-Unidade consumidora desligada"; 
        case "29":
                    return $texto . "-Unidade consumidora não existe"; 
        case "40":
                    return $texto . "-CPF enviado diferente do cadastro"; 
        case "73":
                    return $texto . "-UC com vigência expirada. Cancelado em mês anterior"; 
        case "75":
                    return $texto . "-Rejeição por Troca de Titularidade"; 
        case "80":
                    return $texto . "-Data vencimento prevista (diferente da Data de Vencimento)"; 
        case "85":
                    return $texto . "-Duplicidade"; 
        case "98":
                    return $texto . "-Entrada confirmada"; 
        case "99":
                    return $texto . "-De-Para de identificação do cliente";
        default:
            return $texto;
     }
 }

 function TranslateCmdMovimento($texto)
 {
     switch($texto)
     {
        case "74":
            return $texto . "-Cadastramento da UC";
        case "75":
            return $texto . "-Rejeição por Troca de Titularidade";
        case "77":
            return $texto . "-Cancelamento do plano de convênio";
        case "78":
            return $texto . "-Alteração cadastral ou término de cobertura";                                 
         case "80":
            return $texto . "-Data de Vencimento Prevista (diferente da Data de Vencimento)";
         default:
            return $texto;
     }
 }

 function CreateRecordCelescCadastro($line,$position)
 {
    $celesc = new CelescInfoCadastro();
    $celesc->IdTipoRegistro = trim(substr($line, $position, 1));
    $position = 1;
    $celesc->CodigoUnidadeConsumidora= trim(substr($line, $position, 13)); 
    $position = 14;
    $celesc->ValorLancamento = trim(substr($line, $position, 9)); 
    $position = 23;
    $celesc->DataGeracaoRegistro = trim(substr($line, $position, 8)); 
    $position = 31;
    $celesc->CmdMovimento= TranslateCmdMovimento(trim(substr($line, $position, 2))); 
    $position = 33;
    $celesc->CodigoContaContabil= trim(substr($line, $position, 8)); 
    $position = 41;
    $celesc->Cobertura_Ocorrencia= TranslateCoberturaOcorrencia(trim(substr($line, $position, 2))); 
    $position = 43;
    $celesc->Descricao_Cobertura_Ocorrencia= trim(substr($line, $position, 30)); 
    $position = 73;
    $celesc->NumeroCliente= trim(substr($line, $position, 10)); 
    $position = 83;
    $celesc->IdClienteContratante= trim(substr($line, $position, 6)); 
    $position = 89;
    $celesc->CPF_CPNJ= trim(substr($line, $position, 12)); 
    $position = 101;
    $celesc->MesInicioVigencia= trim(substr($line, $position, 8)); 
    $position = 109;
    $celesc->MesFimVigencia= trim(substr($line, $position, 8));
    $position = 117; 
    $celesc->ComplementoCNPJ= trim(substr($line, $position, 2)); 
    $position = 119;
    $celesc->Espacos= trim(substr($line, $position, 2)); 
    $position = 121;
    $celesc->CodigoUnidadeConsumidoraAnterior= trim(substr($line, $position,13)); 
    $position = 134;
    $celesc->NmumerolienteAnterior= trim(substr($line, $position, 10)); 
    $position = 144;
    $celesc->NumeroSequencialRegistro= trim(substr($line, $position, 6));       
    return $celesc;
 }

 function CreateRecordCelescLancamento($line,$position)
 {
    $celesc = new CelescLancamento();
    $celesc->IdTipoRegistro= trim(substr($line, $position, 1));
    $position = 1;
    $celesc->CodigoUnidadeConsumidora= trim(substr($line, $position, 13)); 
    $position = 14;
    $celesc->ValorLancamento= trim(substr($line, $position, 9)); 
    $position = 23;
    $celesc->DataLancamento= trim(substr($line, $position, 8)); 
    $position = 31;
    $celesc->InformativoLancamento= TranslateInformative(trim(substr($line, $position, 2))); 
    $position = 33;
    $celesc->CodigoConta= trim(substr($line, $position, 8)); 
    $position = 41;
    $celesc->ControleConcessionaria= trim(substr($line, $position, 60)); 
    $position = 101;
    $celesc->CodigoOrigemFatura= trim(substr($line, $position, 3)); 
    $position = 104;
    $celesc->NumeroFatura= trim(substr($line, $position, 17)); 
    $position = 121;
    $celesc->DataVencimentoOuBaixa= trim(substr($line, $position, 8)); 
    $position = 129;
    $celesc->ValorBaseCalculo= trim(substr($line, $position, 15)); 
    $position = 144;
    $celesc->NumeroRegistroSequencial= trim(substr($line, $position, 6)); 
    return $celesc;
 }

//Classe Celesc com os campos conforme a DFD 
class CelescInfoCadastro{
    public $IdTipoRegistro;
    public $CodigoUnidadeConsumidora;
    public $ValorLancamento;
    public $DataGeracaoRegistro;
    public $CmdMovimento;
    public $CodigoContaContabil;
    public $Cobertura_Ocorrencia;
    public $Descricao_Cobertura_Ocorrencia;
    public $NumeroCliente;
    public $IdClienteContratante;
    public $CPF_CPNJ;
    public $MesInicioVigencia;
    public $MesFimVigencia;
    public $ComplementoCNPJ;
    public $Espacos;
    public $CodigoUnidadeConsumidoraAnterior;
    public $NumeroClienteAnterior;
    public $NmumeroequencialRegistro;
}

class CelescLancamento{
    public $IdTipoRegistro;
    public $CodigoUnidadeConsumidora;
    public $ValorLancamento;
    public $DataLancamento;
    public $InformativoLancamento;
    public $CodigoConta;
    public $ControleConcessionaria;
    public $CodigoOrigemFatura;
    public $NumeroFatura;
    public $DataVencimentoOuBaixa;
    public $ValorBaseCalculo;
    public $NumeroRegistroSequencial;
}

//remove ultimo elemento que é o footer
$a = array_pop($my_array);
echo   json_encode($my_array, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE), "\n\n";
 

?>
