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
        }
        
        body{
            font-size:11px;
        }

        img{
            height:100px;
            width:250px;
            position:fixed;
            opacity:0.5;
            margin-left:33%;
            margin-top:20%;
        }
    </style>
</head>
<body>
    <h2>INSTITUTO DE SUELO, URBANIZACIÓN Y VIVIENDA DEL ESTADO DE COLIMA</h2>
    <?php if($credito->tabla == "4"){ ?>
        <h4>TABLA DE AMORTIZACIÓN CON TASA DE INTERÉS ANUAL</h4>
    <?php } else { ?>
        <h4>TABLA DE AMORTIZACIÓN CON PAGOS FIJOS</h4>
    <?php } ?>
    <br><br><br>
    <img src="img/logo_pdf.png">
    <div>
        <table class="full">
            <tbody>
                <tr>
                    <td><b>Cve. Credito: </b></td><td><?php echo $credito->clave_credito?></td>
                    <td><b>Estado:</b></td>
                    <td><?php echo $credito->lote->fraccionamiento->localidad->municipio->estado->nombre ?></td>
                </tr>
                <tr>
                    <td><b>Programa:</b></td><td><?php echo $credito->demanda->tipo_programa->programa->nombre ?></td>
                    <td><b>Municipio:</b></td>
                    <td><?php echo $credito->lote->fraccionamiento->localidad->municipio->nombre ?></td>
                </tr>
                <tr>
                    <td><b>Tipo de programa: </b></td><td><?php echo $credito->demanda->tipo_programa->nombre?></td>
                    <td><b>Localidad:</b></td>
                    <td><?php echo $credito->lote->fraccionamiento->localidad->nombre ?></td>
                </tr>
                <tr>
                    <td><b>CURP:</b></td><td><?php echo $credito->demanda->cliente->curp?></td>
                    <td><b>Fraccionamiento:</b></td>
                    <td><?php echo $credito->lote->fraccionamiento->nombre ?></td>
                </tr>
                <tr>
                    <td><b>Fecha de inicio: </b></td><td><?php echo $credito->fecha_inicio ?></td>
                    <td><b>Calle:</b></td>
                    <td><?php echo $credito->lote->calle . ' #' . $credito->lote->numero ?></td>
                </tr>
                <tr>
                    <td><b>Periodo de pago: </b></td><td>MENSUAL</td>
                    <td><b>Manzana:</b></td>
                    <td><?php echo $credito->lote->no_manzana ?></td>                
                </tr>
                <tr>
                    <td><b>Monto credito: </b></td><td>$<?php echo number_format($credito->costo_contado,2)?></td>
                    <td><b>Lote:</b></td>
                    <td><?php echo $credito->lote->no_lote ?></td> 
                </tr>
                <tr>
                    <td><b>Enganche: </b></td><td>$<?php echo number_format($credito->enganche,2)?></td>
                    <td><b>Superficie:</b></td>
                    <td><?php echo $credito->lote->superficie ."M2" ?></td>
                </tr>
                <tr>
                    <td><b>Subsidio Federal:</b></td>
                    <?php if ($credito->no_subsidio_fed != null){ ?>
                        <td>$<?php echo number_format($credito->subsidios->filter(function($value,$index){return $value->tipo == "FEDERAL";})->first()->valor,2)?></td>
                    <?php } else { ?>
                        <td>$0.00</td>
                    <?php } ?>
                    <td><b>Subsidio Estatal:</b></td>
                    <?php if ($credito->no_subsidio_est != null){ ?>
                        <td>$<?php echo number_format($credito->subsidios->filter(function($value,$index){return $value->tipo == "ESTATAL";})->first()->valor,2)?></td>
                    <?php } else { ?>
                        <td>$0.00</td>
                    <?php } ?>
                </tr>
                <tr>
                    <td><b>Subsidio Municipal:</b></td>
                    <?php if ($credito->no_subsidio_mun != null){ ?>
                        <td>$<?php echo number_format($credito->subsidios->filter(function($value,$index){return $value->tipo == "MUNICIPAL";})->first()->valor,2)?></td>
                    <?php } else { ?>
                        <td>$0.00</td>
                    <?php } ?>
                    <td><b>Subsidio Otro:</b></td>
                    <?php if ($credito->no_subsidio_otr != null){ ?>
                        <td>$<?php echo number_format($credito->subsidios->filter(function($value,$index){return $value->tipo == "OTROS";})->first()->valor,2)?></td>
                    <?php } else { ?>
                        <td>$0.00</td>
                    <?php } ?>
                </tr>
                <tr>
                    <td><b>Plazo: </b></td><td><?php echo $credito->plazo?></td>
                </tr>
                <tr>
                    <td><b>Interés anual:</b></td><td><?php echo number_format($credito->taza_interes * 100,2)?>%</td>
                </tr>
                <tr>
                    <td><b>Financiamiento:</b></td><td>$<?php echo number_format($credito->costo_financiamiento,2)?></td>
                </tr>
                <tr>
                    <td><b>Saldo a pagar:</b></td><td>$<?php echo number_format($credito->total_pagar,2)?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <table class="pagos">
        <thead>
            <tr>
                <th>Mensualidad</th>
                <th>Fecha</th>
                <th>Contado</th>
                <?php if($credito->tabla == "4") { ?>
                    <th>Amortiza Capital</th>
                    <th>Interés</th>
                <?php } ?>
                <th>Pago Mensual</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mensualidades as $men) { ?>
                <tr> 
                    <td class="pagos"><?php echo $men->no_mensualidad?></td>
                    <td class="pagos"><?php echo date("d/m/Y", strtotime($men->fecha_vencimiento)) ?></td>
                    <td class="pagos">$<?php echo number_format($men->saldo,2)?></td>
                    <?php if($credito->tabla == "4") { ?>
                        <td class="pagos">$<?php echo number_format($men->capital,2)?></td>
                        <td class="pagos">$<?php echo number_format($men->interes,2)?></td>
                    <?php } ?>
                    <td class="pagos">$<?php echo number_format($credito->pago_mensual,2)?></td>
                    <td class="pagos">$<?php echo number_format($men->resto,2)?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>