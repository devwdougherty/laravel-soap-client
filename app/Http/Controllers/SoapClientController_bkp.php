<?php

namespace App\Http\Controllers;

use stdClass;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\Models\PositronDevice;
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
        $this->handle();

        return response()->json(['status' => 'ok'], 200);   
    }

     /**
     * Busca dados na end point "Mensageira" da positron
     */
    private function requestSoap()
    {
        $username = env('POSITRON_USERNAME');
        $password = env('POSITRON_PASSWORD');
        $queuename = 'fila_guinchos_campos_gerais';

        $headers = ["Content-Type" => "text/xml;charset=utf-8"];
        $options = [];
        $options['body'] = <<<XML
            <soapenv:Envelope
                xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:pos="http://pst.com.br/position.xsd">
            <soapenv:Header/>
                <soapenv:Body>
                    <pos:getOccurrences>
                        <request>
                            <username>$username</username>
                            <password>$password</password>
                            <queuename>$queuename</queuename>
                        </request>
                    </pos:getOccurrences>
                </soapenv:Body>
            </soapenv:Envelope>
        XML;

        $xml = Http::withHeaders($headers)->send("POST", env('POSITRON_URL'), $options);
        $xml = str_replace(
            ['&lt;', '&gt;', '</occurrences><occurrences>', '</occurrences></pst:OccurrenceResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>'],
            ['<', '>', '', ''],
            $xml
        );

        $xml = explode('<?xml version="1.0" encoding="UTF-8" standalone="no" ?>', $xml);
        array_shift($xml);

        $this->xml = $xml;
    }

    /**
     * Salvar dados da localização em cache
     * @param string $placa - Placa do veiculo
     * @param string $novaLatitude - Latitude atualizada retornada pela positron
     * @param string $novaLongitude - Longitude atualizada retornada pela positron
     */
    private function storeCache($placa, $novaLatitude, $novaLongitude)
    {
        if (Cache::has($placa)) {

            $veiculoCache = Cache::get($placa);
            if ($veiculoCache['latitude'] != $novaLatitude || $veiculoCache['longitude'] != $novaLongitude) {
                $veiculoCache['latitude'] = $novaLatitude;
                $veiculoCache['longitude'] = $novaLongitude;
            }
            $expiraEm = now()->addMinutes(30);

            Cache::put($placa, $veiculoCache, $expiraEm);

        } else {
            $veiculo = [
                'latitude' => $novaLatitude,
                'longitude' => $novaLongitude,
            ];

            $expiraEm = now()->addMinutes(30);
            Cache::put($placa, $veiculo, $expiraEm);
        }
    }

    private function storeDatabase($placa, $novaLatitude, $novaLongitude)
    {
        $device = PositronDevice::updateOrCreate(
            ['placa' => $placa],
            ['latitude' => $novaLatitude, 'longitude' => $novaLongitude]
        );

        if ($device) {
            Log::info('Dados salvos no banco de dados com sucesso.');
        } else {
            Log::error('Erro ao salvar dados no banco de dados.');
        }
    }

    /**
     * Proccess
     */
    public function handle()
    {

        $this->requestSoap();

        foreach($this->xml as $xmlItem) {
            $item          = simplexml_load_string($xmlItem);
            $placa         = 'POSITRON_' . ((array) $item->pack)['@attributes']['veicTag'];
            $novaLatitude  = (string) $item->pack->GPS->lat;
            $novaLongitude = (string) $item->pack->GPS->long;

            $this->storeCache($placa, $novaLatitude, $novaLongitude);
            $this->storeDatabase($placa, $novaLatitude, $novaLongitude);
        }
        Log::info('Job executado em: ' . now());

    }
}
