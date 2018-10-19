<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        table.pagos{
            width:100%;
            border-collapse:collapse;
        }

        td.pagos{
            padding: 5px;
            text-align:center;
            /* font-weight:bold; */
        }

        th{
            padding: 5px;
            border-bottom: 1px solid #000;
            border-top: 1px solid #000;
        }

        h4,h2{
            text-align:center;
            font-family:arial;
        }

        h4{
            margin-top:-10px;
            font-weight:normal;
        }

        /* div.imagen{
            background-image: url("img/logo.png");background-size: 250px 100px;background-repeat: no-repeat;background-position:center;
        } */

        /* .right{
            text-align:right;
        } */

        .full{
            width:100%;
            border-collapse: collapse;
        }
        
        body{
            font-size:11px;
        }

        img{
            height:75px;
            width:225px;
            position:fixed;
            opacity:0.5;
            margin-left:5%;
            margin-top:4%;
        }
        .border{
            border: 1px solid black;
            text-align: center;
        }
        .padding{
            padding: 5px;
        }
        .width{
            width: 40%;
        }
        .left{
            text-align: left;
        }
        .marginP{
            margin: -1px 0 -1px 0;
        }

        .UpDown{
            border-top: none;
            border-bottom: none;
        }
    </style>
</head>
<body>
    <h2>INSTITUTO DE SUELO, URBANIZACIÓN Y VIVIENDA DEL ESTADO DE COLIMA</h2>
    <h4>CEDULA DE CONTRATACION DE ESCRITURAS</h4>
    <img src="img/logo_pdf.png">
    <div>
        <table class="full">
            <tbody>
                <tr>
                    <td colspan="4" class="width"></td>
                    <td class="border">FOLIO</td>
                    <td class="border">DIA</td>
                    <td class="border">MES</td>
                    <td class="border">AÑO</td>
                </tr>
                <tr>
                    <td colspan="4" class="width"></td>
                    <td class="border padding"><?php echo $clave ?></td>
                    <td class="border padding"><?php echo $dia ?></td>
                    <td class="border padding"><?php echo $mes ?></td>
                    <td class="border padding"><?php echo $anio ?></td>
                </tr>
                <tr><td colspan="8" style="padding:10px;"></td></tr><!-- Vacio -> Separador -->
                <tr>
                    <td colspan="6"class="border padding">
                        <p class="left marginP">COLONIA</p>
                        <p class="marginP"><?php echo $credito->lote->fraccionamiento->nombre ?></p>
                    </td>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">CLAVE </p>
                        <p class="marginP"><?php echo $credito->clave_credito ?> </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"class="border padding">
                        <p class="left marginP">NOMBRE</p>
                        <p class="marginP"><?php echo $credito->demanda->cliente->nombre . " " . $credito->demanda->cliente->ape_paterno . " " . $credito->demanda->cliente->ape_materno?></p>
                    </td>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">TELEFONO</p>
                        <p class="marginP"><?php echo $credito->demanda->cliente->telefono ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"class="border padding">
                        <p class="left marginP">DOMICILIO</p>
                        <p class="marginP"><?php echo $credito->lote->calle . " #" . $credito->lote->numero . ", ". $credito->lote->fraccionamiento->nombre ?></p>
                    </td>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">LUGAR DE NACIMIENTO</p>
                        <p class="marginP"><?php echo $credito->demanda->cliente->lugar_nac ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"class="border padding">
                        <p class="left marginP">MUNICIPIO</p>
                        <p class="marginP"><?php echo $credito->lote->fraccionamiento->localidad->municipio->nombre ?></p>
                    </td>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">ESTADO</p>
                        <p class="marginP"><?php echo $credito->lote->fraccionamiento->localidad->municipio->estado->nombre ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">FECHA NACIMIENTO</p>
                        <p class="marginP"><?php echo $credito->demanda->cliente->fecha_nac ?></p>
                    </td>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">ESTADO CIVIL</p>
                        <p class="marginP"><?php echo $credito->demanda->cliente->estado_civil ?></p>
                    </td>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">OCUPACION</p>
                        <p class="marginP"><?php echo $credito->demanda->cliente->ocupacion->nombre ?></p>
                    </td>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">SEXO</p>
                        <p class="marginP"><?php echo $credito->demanda->cliente->genero ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">NO. MANZANA</p>
                        <p class="marginP"><?php echo $credito->lote->no_manzana ?></p>
                    </td>
                    <td colspan="1"class="border padding">
                        <p class="left marginP">NO. LOTE</p>
                        <p class="marginP"><?php echo $credito->lote->no_lote ?></p>
                    </td>
                    <td colspan="3"class="border padding">
                        <p class="left marginP">CLAVE CATASTRAL</p>
                        <p class="marginP"><?php if($credito->lote->clave_catastral != null) echo $credito->lote->clave_catastral; else echo "SIN REGISTRO"; ?></p>
                    </td>
                    <td colspan="1"class="border padding">
                        <p class="left marginP">SUPERFICIE</p>
                        <p class="marginP"><?php echo $credito->lote->superficie ?> M2</p>
                    </td>
                    <td colspan="1"class="border padding">
                        <p class="left marginP">VALOR/M2</p>
                        <p class="marginP">$<?php if($credito->lote->regularizacion == 0) echo number_format($credito->costo_metro,2); else echo number_format($credito->lote->regularizar->valor_metro,2); ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"class="border padding">
                        <b>VALOR CATASTRAL</b>
                    </td>
                    <td colspan="4"class="border padding">
                        <b>VALOR OPERACION INSUVI</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">LOTE</p>
                        <p class="marginP">$<?php if($credito->lote->regularizacion != 0) echo number_format($credito->lote->regularizar->catastral_lote,2); else echo "0.00"; ?></p>
                    </td>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">CONSTRUCCION</p>
                        <p class="marginP">$<?php if($credito->lote->regularizacion != 0) echo number_format($credito->lote->regularizar->catastral_construccion,2); else echo number_format($credito->costo_construccion,2);?></p>
                    </td>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">LOTE</p>
                        <p class="marginP">$<?php if($credito->lote->regularizacion != 0) echo number_format($credito->lote->regularizar->insuvi_lote,2); else echo "0.00"; ?></p>
                    </td>
                    <td colspan="2"class="border padding">
                        <p class="left marginP">PIE DE CASA</p>
                        <p class="marginP">$<?php if($credito->lote->regularizacion != 0) echo number_format($credito->lote->regularizar->insuvi_pie_casa,2); else echo "0.00"; ?></p>
                    </td>
                </tr>
                <tr><td colspan="8" style="padding:25px;"></td></tr><!-- Vacio -> Separador -->
                <tr>
                    <td colspan="8" class="border" style="padding:15px;">
                        <p class="left" style="margin:-10px;">COLINDANCIAS</p>
                        <p style="margin:0px;">AL ESTE: <?php echo $credito->lote->este ?></p>
                        <p style="margin:0px;">AL NORTE: <?php echo $credito->lote->norte ?></p>
                        <p style="margin:0px;">AL OESTE: <?php echo $credito->lote->oeste ?></p>
                        <p style="margin:0px;">AL SUR: <?php echo $credito->lote->sur ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align:center;padding:25px 0 25px 0;">EL BENEFICIARIO SE OBLIGA A DARSE DE ALTA ANTE CATASTRO MUNICIPAL PARA CUBRIR EL PAGO DEL IMPUESTO PREDIAL; Y ASI MISMO A PRESENTAR COPIA DEL RECIBO O CORRESPONDIENTE ANTE EL IVECOL PARA TRAMITAR LA ESCRITURACION DEL INMUEBLE QUE AMPARA EL PRESENTE CONTRATO</td>
                </tr>
                <tr>
                    <td colspan="3" class="border padding">
                        <p style="margin:0px;">CONTRATANTE</p>
                        <br><br><br><!-- ESPACIO PARA FIRMAR-->
                        <p style="margin:0px;"><?php echo $credito->demanda->cliente->nombre . " " . $credito->demanda->cliente->ape_paterno . " " . $credito->demanda->cliente->ape_materno?></p>
                        <p style="margin:0px;">NOMBRE Y FIRMA</p>
                    </td>
                    <td colspan="3" class="border padding">
                        <p style="margin:0px;">ELABORADO POR</p>
                        <br><br><br><!-- ESPACIO PARA FIRMAR-->
                        <p style="margin:0px;">CARMEN SILVIA HERNANDEZ VIRGEN</p>
                        <p style="margin:0px;">NOMBRE Y FIRMA</p>
                    </td>
                    <td colspan="2" class="border padding">
                        <p style="margin:0px;">REVISADO</p>
                        <br><br><br><!-- ESPACIO PARA FIRMAR-->
                        <p style="margin:0px;">T.A. LUIS MIGUEL HUERTA CHAVEZ</p>
                        <p style="margin:0px;">NOMBRE Y FIRMA</p>
                    </td>
                </tr>
                <tr><td colspan="8" style="padding:25px;"></td></tr><!-- Vacio -> Separador -->
                <tr>
                    <td colspan="4" class="border padding">
                        <p class="left marginP">FORMA DE PAGO</p>
                        <p class="marginP"><?php if($credito->mensualidades->count() > 1) echo "CREDITO"; else echo "CONTADO";?></p>
                    </td>
                    <td colspan="2" class="border padding">
                        <p class="left marginP">OCUPADO</p>
                        <p class="marginP">SI</p>
                    </td>
                    <td colspan="2" class="border padding">
                        <p class="left marginP">GASTOS DE ESCRITURACION</p>
                        <p class="marginP">$<?php echo number_format($credito->total_pagar,2) ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="border padding" style="border-bottom:none;">No. DE DOCTOS A PAGAR</td>
                    <td colspan="2" class="border padding" style="border-bottom:none;">IMPORTE DE LOS DOCTOS</td>
                    <td colspan="2" class="border padding" style="border-bottom:none;">FECHA DEL 1ER DOCTO</td>
                    <td class="border padding" style="border-bottom:none;">FRECUENCIA DE PAGO</td>
                    <td class="border padding">
                        <p class="left marginP">SUB-TOTAL</p>
                        <p class="marginP">$<?php echo number_format($credito->valor_solucion,2) ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="border padding UpDown"><?php echo $credito->mensualidades->count() ?></td>
                    <td colspan="2" class="border padding UpDown">$<?php echo number_format($credito->total_pagar,2) ?></td>
                    <td colspan="2" class="border padding UpDown"><?php echo $credito->fecha_inicio ?></td>
                    <td class="border padding UpDown">MENSUAL</td>
                    <td class="border padding">
                        <p class="left marginP">ENGANCHE</p>
                        <p class="marginP">$<?php echo number_format($credito->enganche,2) ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="border padding UpDown"></td>
                    <td colspan="2" class="border padding UpDown"></td>
                    <td colspan="2" class="border padding UpDown"></td>
                    <td class="border padding UpDown"></td>
                    <td class="border padding">
                        <p class="left marginP">INTERES</p>
                        <p class="marginP">$<?php echo number_format($credito->costo_financiamiento,2) ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="border padding" style="border-top:none;"></td>
                    <td colspan="2" class="border padding" style="border-top:none;"></td>
                    <td colspan="2" class="border padding" style="border-top:none;"></td>
                    <td class="border padding" style="border-top:none;"></td>
                    <td class="border padding">
                        <p class="left marginP">SALDO A PAGAR</p>
                        <p class="marginP">$<?php echo number_format($credito->total_pagar,2) ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>