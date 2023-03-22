<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SascarPacotePosicaoJsonComPlaca extends Model
{
    use HasFactory;

    protected $fillable = [
        'idVeiculo',
        'dataPosicao',
        'dataPacote',
        'latitude',
        'longitude',
        'direcao',
        'velocidade',
        'ignicao',
        'odometro',
        'horimetro',
        'tensao',
        'saida1',
        'saida2',
        'saida3',
        'saida4',
        'entrada1',
        'entrada2',
        'entrada3',
        'entrada4',
        'satelite',
        'memoria',
        'idReferencia',
        'bloqueio',
        'gps',
        'uf',
        'cidade',
        'rua',
        'pais',
        'pontoReferencia',
        'anguloReferencia',
        'distanciaReferencia',
        'rpm',
        'temperatura1',
        'temperatura2',
        'temperatura3',
        'saida5',
        'saida6',
        'saida7',
        'saida8',
        'entrada5',
        'entrada6',
        'entrada7',
        'entrada8',
        'pontoEntrada',
        'pontoSaida',
        'codigoMacro',
        'nomeMensagem',
        'conteudoMensagem',
        'textoMensagem',
        'tipoTeclado',
        'eventoSequenciamento',
        'eventos',
        'jamming',
        'statusAncora',
        'idPacote',
        'integradoraId',
        'idMotorista',
        'nomeMotorista',
        'nivelCombustivel',
        'litrometro',
        'estadoLimpadorParabrisa',
        'umidadeSerial',
        'temperaturaSerial',
        'placa',
    ];

    protected $casts = [
        'eventoSequenciamento' => 'json',
        'eventos' => 'json',
    ];
}
