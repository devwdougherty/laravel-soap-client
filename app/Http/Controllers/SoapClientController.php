<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\Models\PositronDevice;
use App\Models\SascarPacotePosicaoJsonComPlaca;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SoapClientController extends Controller
{

    /** @var array Itens (XML) */
    private $xml;

    public function getPosition(Request $request)
    {
        $objectReturn = $this->requestSoap();
        $sascarPacotePosicaoJsonComPlacaList = [];

        foreach($objectReturn as $jsonObject) {
            Log::debug("/getPosition");
            Log::debug(json_encode($jsonObject));
            //$sascarPacotePosicaoJsonObject = json_decode($jsonObject, true);
            $sascarPacotePosicaoJsonObject = $jsonObject;
            $sascarPacotePosicaoJsonComPlaca = new SascarPacotePosicaoJsonComPlaca();
            
            // map each property to the model
            $sascarPacotePosicaoJsonComPlaca->id_veiculo = $sascarPacotePosicaoJsonObject['idVeiculo'];
            $sascarPacotePosicaoJsonComPlaca->data_posicao = $sascarPacotePosicaoJsonObject['dataPosicao'];
            $sascarPacotePosicaoJsonComPlaca->data_pacote = $sascarPacotePosicaoJsonObject['dataPacote'];
            $sascarPacotePosicaoJsonComPlaca->latitude = $sascarPacotePosicaoJsonObject['latitude'];
            $sascarPacotePosicaoJsonComPlaca->longitude = $sascarPacotePosicaoJsonObject['longitude'];
            $sascarPacotePosicaoJsonComPlaca->direcao = $sascarPacotePosicaoJsonObject['direcao'];
            $sascarPacotePosicaoJsonComPlaca->velocidade = $sascarPacotePosicaoJsonObject['velocidade'];
            $sascarPacotePosicaoJsonComPlaca->ignicao = $sascarPacotePosicaoJsonObject['ignicao'];
            $sascarPacotePosicaoJsonComPlaca->odometro = $sascarPacotePosicaoJsonObject['odometro'];
            $sascarPacotePosicaoJsonComPlaca->horimetro = $sascarPacotePosicaoJsonObject['horimetro'];
            $sascarPacotePosicaoJsonComPlaca->tensao = $sascarPacotePosicaoJsonObject['tensao'];
            $sascarPacotePosicaoJsonComPlaca->saida1 = $sascarPacotePosicaoJsonObject['saida1'];
            $sascarPacotePosicaoJsonComPlaca->saida2 = $sascarPacotePosicaoJsonObject['saida2'];
            $sascarPacotePosicaoJsonComPlaca->saida3 = $sascarPacotePosicaoJsonObject['saida3'];
            $sascarPacotePosicaoJsonComPlaca->saida4 = $sascarPacotePosicaoJsonObject['saida4'];
            $sascarPacotePosicaoJsonComPlaca->entrada1 = $sascarPacotePosicaoJsonObject['entrada1'];
            $sascarPacotePosicaoJsonComPlaca->entrada2 = $sascarPacotePosicaoJsonObject['entrada2'];
            $sascarPacotePosicaoJsonComPlaca->entrada3 = $sascarPacotePosicaoJsonObject['entrada3'];
            $sascarPacotePosicaoJsonComPlaca->entrada4 = $sascarPacotePosicaoJsonObject['entrada4'];
            $sascarPacotePosicaoJsonComPlaca->satelite = $sascarPacotePosicaoJsonObject['satelite'];
            $sascarPacotePosicaoJsonComPlaca->memoria = $sascarPacotePosicaoJsonObject['memoria'];
            $sascarPacotePosicaoJsonComPlaca->id_referencia = $sascarPacotePosicaoJsonObject['idReferencia'];
            $sascarPacotePosicaoJsonComPlaca->bloqueio = $sascarPacotePosicaoJsonObject['bloqueio'];
            $sascarPacotePosicaoJsonComPlaca->gps = $sascarPacotePosicaoJsonObject['gps'];
            $sascarPacotePosicaoJsonComPlaca->uf = $sascarPacotePosicaoJsonObject['uf'];
            $sascarPacotePosicaoJsonComPlaca->cidade = $sascarPacotePosicaoJsonObject['cidade'];
            $sascarPacotePosicaoJsonComPlaca->rua = $sascarPacotePosicaoJsonObject['rua'];
            $sascarPacotePosicaoJsonComPlaca->pais = $sascarPacotePosicaoJsonObject['pais'];
            $sascarPacotePosicaoJsonComPlaca->ponto_referencia = $sascarPacotePosicaoJsonObject['pontoReferencia'];
            $sascarPacotePosicaoJsonComPlaca->angulo_referencia = $sascarPacotePosicaoJsonObject['anguloReferencia'];
            $sascarPacotePosicaoJsonComPlaca->distancia_referencia = $sascarPacotePosicaoJsonObject['distanciaReferencia'];
            $sascarPacotePosicaoJsonComPlaca->rpm = $sascarPacotePosicaoJsonObject['rpm'];
            $sascarPacotePosicaoJsonComPlaca->temperatura1 = $sascarPacotePosicaoJsonObject['temperatura1'];
            $sascarPacotePosicaoJsonComPlaca->temperatura2 = $sascarPacotePosicaoJsonObject['temperatura2'];
            $sascarPacotePosicaoJsonComPlaca->temperatura3 = $sascarPacotePosicaoJsonObject['temperatura3'];
            $sascarPacotePosicaoJsonComPlaca->saida5 = $sascarPacotePosicaoJsonObject['saida5'];
            $sascarPacotePosicaoJsonComPlaca->saida6 = $sascarPacotePosicaoJsonObject['saida6'];
            $sascarPacotePosicaoJsonComPlaca->saida7 = $sascarPacotePosicaoJsonObject['saida7'];
            $sascarPacotePosicaoJsonComPlaca->saida8 = $sascarPacotePosicaoJsonObject['saida8'];
            $sascarPacotePosicaoJsonComPlaca->entrada5 = $sascarPacotePosicaoJsonObject['entrada5'];
            $sascarPacotePosicaoJsonComPlaca->entrada6 = $sascarPacotePosicaoJsonObject['entrada6'];
            $sascarPacotePosicaoJsonComPlaca->entrada7 = $sascarPacotePosicaoJsonObject['entrada7'];
            $sascarPacotePosicaoJsonComPlaca->entrada8 = $sascarPacotePosicaoJsonObject['entrada8'];
            $sascarPacotePosicaoJsonComPlaca->ponto_entrada = $sascarPacotePosicaoJsonObject['pontoEntrada'];
            $sascarPacotePosicaoJsonComPlaca->ponto_saida = $sascarPacotePosicaoJsonObject['pontoSaida'];
            $sascarPacotePosicaoJsonComPlaca->codigo_macro = $sascarPacotePosicaoJsonObject['codigoMacro'];
            $sascarPacotePosicaoJsonComPlaca->nome_mensagem = $sascarPacotePosicaoJsonObject['nomeMensagem'];
            $sascarPacotePosicaoJsonComPlaca->conteudo_mensagem = $sascarPacotePosicaoJsonObject['conteudoMensagem'];
            $sascarPacotePosicaoJsonComPlaca->texto_mensagem = $sascarPacotePosicaoJsonObject['textoMensagem'];
            $sascarPacotePosicaoJsonComPlaca->tipo_teclado = $sascarPacotePosicaoJsonObject['tipoTeclado'];
            $sascarPacotePosicaoJsonComPlaca->evento_sequenciamento = $sascarPacotePosicaoJsonObject['eventoSequenciamento'];
            $sascarPacotePosicaoJsonComPlaca->eventos = $sascarPacotePosicaoJsonObject['eventos'];
            $sascarPacotePosicaoJsonComPlaca->jamming = $sascarPacotePosicaoJsonObject['jamming'];
            $sascarPacotePosicaoJsonComPlaca->status_ancora = $sascarPacotePosicaoJsonObject['statusAncora'];
            $sascarPacotePosicaoJsonComPlaca->id_pacote = $sascarPacotePosicaoJsonObject['idPacote'];
            $sascarPacotePosicaoJsonComPlaca->integradora_id = $sascarPacotePosicaoJsonObject['integradoraId'];
            $sascarPacotePosicaoJsonComPlaca->id_motorista = $sascarPacotePosicaoJsonObject['idMotorista'];
            $sascarPacotePosicaoJsonComPlaca->nome_motorista = $sascarPacotePosicaoJsonObject['nomeMotorista'];
            $sascarPacotePosicaoJsonComPlaca->nivel_combustivel = $sascarPacotePosicaoJsonObject['nivelCombustivel'];
            $sascarPacotePosicaoJsonComPlaca->litrometro = $sascarPacotePosicaoJsonObject['litrometro'];
            $sascarPacotePosicaoJsonComPlaca->estado_limpador_parabrisa = $sascarPacotePosicaoJsonObject['estadoLimpadorParabrisa'];
            $sascarPacotePosicaoJsonComPlaca->umidade_serial = $sascarPacotePosicaoJsonObject['umidadeSerial'];
            $sascarPacotePosicaoJsonComPlaca->temperatura_serial = $sascarPacotePosicaoJsonObject['temperaturaSerial'];
            $sascarPacotePosicaoJsonComPlaca->placa = $sascarPacotePosicaoJsonObject['placa'];

            array_push($sascarPacotePosicaoJsonComPlacaList, $sascarPacotePosicaoJsonComPlaca);
        }       

        return response()->json(['response' => $sascarPacotePosicaoJsonComPlacaList], 200);   
    }

     /**
     * Busca dados na end point "Mensageira" da positron
     */
    private function requestSoap()
    {
        $username = env('SASCAR_USERNAME');
        $password = env('SASCAR_PASSWORD');
        $url = env('SASCAR_URL');

        $headers = ["Content-Type" => "text/xml;charset=utf-8"];
        $options = [];
        $options['body'] = <<<XML
            <soapenv:Envelope
                xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:ws="http://webservice.web.integracao.sascar.com.br/">
            <soapenv:Header/>
                <soapenv:Body>
                    <ws:obterPacotePosicoesJSONComPlaca>
                        <usuario>$username</usuario>
                        <senha>$password</senha>
                        <quantidade>10</quantidade>
                    </ws:obterPacotePosicoesJSONComPlaca>
                </soapenv:Body>
            </soapenv:Envelope>
        XML;

        $xml = Http::withHeaders($headers)->send("POST", $url, $options);
        Log::debug($xml);
       
        $xmlLoadString = simplexml_load_string($xml);

        $jsonObjects = [];

        foreach($xmlLoadString->xpath('//return') as $return) {
            $jsonObjects[] = json_decode((string)$return, true);
        }
        
        //Log::debug(json_encode($jsonObjects));
        //var_dump($jsonObjects);

        return $jsonObjects;
    }
}
