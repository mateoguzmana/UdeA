<?php

session_start();
$_SESSION['zona'] = Yii::app()->user->_idZone;
$quantity = count($candidatos);
$count = 0;

?>
<style type="text/css">
td img{
    cursor: pointer;
}
</style>
<div class="pageheader">
    <h2>
        <a style="text-decoration: none;" class="salirCandidatos">
            <img src="images/elections2.png" class="cursorpointer" style="width: 38px; margin-right: 15px; margin-left: 15px;"/> 
        </a>
        <?php echo strtoupper($eleccion['Descripcion']); ?><span></span></h2>      
</div>

<div class="contentpanel">
    <div class="panel-heading">
        <div class="widget widget-blue">
            <div class="widget-content">
                <form id="candidateToVote" method="POST" action="">
                    <input type="hidden" name="zone" id="zone" value="<?php echo Yii::app()->user->_idZone; ?>">
                    <input type="hidden" name="candidate" id="candidate">
                </form>
                <?php foreach ($nombreZona as $zona): ?>
                <div class="row">
                    <div class="col-md-12">
                        <h2 style="text-align:center;font-family:'Times New Roman';">
                            Zona Electoral: <?php echo ucfirst($zona['Nombre']); ?>
                        </h2>
                    </div>
                </div>
                <?php
                $cantidadCandidatos = count($candidatos[$count]);
                $modulo = $cantidadCandidatos%4;
                $diferencia = $cantidadCandidatos - $modulo;
                ?>
                <table id="tbCandidatosZona">
                    <?php for ($i=0; $i<$cantidadCandidatos; $i+=4): ?>
                    <?php if ($i == $diferencia): ?>
                    <tr>
                        <td onclick="loadList2(<?=$candidatos[$count][$i]['IdCandidato']?>, '<?=$candidatos[$count][$i]["Nombres"]?>')" class="col-md-2">
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/Archivos/Fotos/<?php echo $candidatos[$count][$i]['Foto']; ?>" height="200" width="100%" />
                        </td>
                        <?php if ($modulo == 2): ?>
                        <td class="col-md-1 separateColumn"></td>
                        <td onclick="loadList2(<?=$candidatos[$count][$i+1]['IdCandidato']?>, '<?=$candidatos[$count][$i+1]["Nombres"]?>')" class="col-md-2">
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/Archivos/Fotos/<?php echo $candidatos[$count][$i+1]['Foto']; ?>" height="200" width="100%" />
                        </td>
                        <?php elseif ($modulo == 3): ?>
                        <td class="col-md-1 separateColumn"></td>
                        <td onclick="loadList2(<?=$candidatos[$count][$i+1]['IdCandidato']?>, '<?=$candidatos[$count][$i+1]["Nombres"]?>')" class="col-md-2">
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/Archivos/Fotos/<?php echo $candidatos[$count][$i+1]['Foto']; ?>" height="200" width="100%" />
                            </label>
                        </td>
                        <td class="col-md-1 separateColumn"></td>
                        <td onclick="loadList2(<?=$candidatos[$count][$i+2]['IdCandidato']?>, '<?=$candidatos[$count][$i+2]["Nombres"]?>')" class="col-md-2">
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/Archivos/Fotos/<?php echo $candidatos[$count][$i+2]['Foto']; ?>" height="200" width="100%" />
                        </td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td class="col-md-2">
                            <label>
                                <?php if($candidatos[$count][$i]['IdCandidato']!=89){?>
                                <button onclick="loadList2(<?=$candidatos[$count][$i]['IdCandidato']?>, '<?=$candidatos[$count][$i]["Nombres"]?>')" class="btn" style="background-color:#006761;color:white;">Ver plancha</button><br>
                                <?php } ?>
                                <span style="font-size:1.2em;">
                                    <?php
                                    if ($candidatos[$count][$i]['IdCandidato'] == 89):
                                        echo $candidatos[$count][$i]['Nombres'];
                                    else:
                                        echo $candidatos[$count][$i]['IdCandidato'] . " - " . $candidatos[$count][$i]['Nombres'];
                                    endif;
                                    ?>
                                </span><br>
                                <input type="radio" value="<?php echo $candidatos[$count][$i]['IdCandidato']; ?>" name="candidato">
                            </label>
                        </td>
                        <?php if ($modulo == 2): ?>
                        <td class="col-md-1 separateColumn"></td>
                        <td class="col-md-2">
                            <label>
                                <?php if($candidatos[$count][$i+1]['IdCandidato']!=89){?>
                                <button onclick="loadList2(<?=$candidatos[$count][$i+1]['IdCandidato']?>, '<?=$candidatos[$count][$i+1]["Nombres"]?>')" class="btn" style="background-color:#006761;color:white;">Ver plancha</button><br>
                                <?php } ?>
                                <span style="font-size:1.2em;">
                                    <?php
                                    if ($candidatos[$count][$i+1]['IdCandidato'] == 89):
                                        echo $candidatos[$count][$i+1]['Nombres'];
                                    else:
                                        echo $candidatos[$count][$i+1]['IdCandidato'] . " - " . $candidatos[$count][$i+1]['Nombres'];
                                    endif;
                                    ?>
                                </span><br>
                                <input type="radio" value="<?php echo $candidatos[$count][$i+1]['IdCandidato']; ?>" name="candidato">
                            </label>
                        </td>
                        <?php elseif ($modulo == 3): ?>
                        <td class="col-md-1 separateColumn"></td>
                        <td class="col-md-2">
                            <label>
                                <?php if($candidatos[$count][$i+1]['IdCandidato']!=89){?>
                                <button onclick="loadList2(<?=$candidatos[$count][$i+1]['IdCandidato']?>, '<?=$candidatos[$count][$i+1]["Nombres"]?>')" class="btn" style="background-color:#006761;color:white;">Ver plancha</button><br>
                                <?php } ?>
                                <span style="font-size:1.2em;">
                                    <?php echo $candidatos[$count][$i+1]['IdCandidato'] . " - " . $candidatos[$count][$i+1]['Nombres']; ?>
                                </span><br>
                                <input type="radio" value="<?php echo $candidatos[$count][$i+1]['IdCandidato']; ?>" name="candidato">
                            </label>
                        </td>
                        <td class="col-md-1 separateColumn"></td>
                        <td class="col-md-2">
                            <label>
                                <?php if($candidatos[$count][$i+2]['IdCandidato']!=89){?>
                                <button onclick="loadList2(<?=$candidatos[$count][$i+2]['IdCandidato']?>, '<?=$candidatos[$count][$i+2]["Nombres"]?>')" class="btn" style="background-color:#006761;color:white;">Ver plancha</button><br>
                                <?php } ?>
                                <span style="font-size:1.2em;">
                                    <?php
                                    if ($candidatos[$count][$i+2]['IdCandidato'] == 89):
                                        echo $candidatos[$count][$i+2]['Nombres'];
                                    else:
                                        echo $candidatos[$count][$i+2]['IdCandidato'] . " - " . $candidatos[$count][$i+2]['Nombres'];
                                    endif;
                                    ?>
                                </span><br>
                                <input type="radio" value="<?php echo $candidatos[$count][$i+2]['IdCandidato']; ?>" name="candidato">
                            </label>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td onclick="loadList2(<?=$candidatos[$count][$i]['IdCandidato']?>, '<?=$candidatos[$count][$i]["Nombres"]?>')" class="col-md-2">
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/Archivos/Fotos/<?php echo $candidatos[$count][$i]['Foto']; ?>" height="200" width="100%" />
                        </td>
                        <td class="col-md-1 separateColumn"></td>
                        <td onclick="loadList2(<?=$candidatos[$count][$i+1]['IdCandidato']?>, '<?=$candidatos[$count][$i+1]["Nombres"]?>')" class="col-md-2">
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/Archivos/Fotos/<?php echo $candidatos[$count][$i+1]['Foto']; ?>" height="200" width="100%" />
                        </td>
                        <td class="col-md-1 separateColumn"></td>
                        <td onclick="loadList2(<?=$candidatos[$count][$i+2]['IdCandidato']?>, '<?=$candidatos[$count][$i+2]["Nombres"]?>')" class="col-md-2">
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/Archivos/Fotos/<?php echo $candidatos[$count][$i+2]['Foto']; ?>" height="200" width="100%" />
                        </td>
                        <td class="col-md-1 separateColumn"></td>
                        <td onclick="loadList2(<?=$candidatos[$count][$i+3]['IdCandidato']?>, '<?=$candidatos[$count][$i+3]["Nombres"]?>')" class="col-md-2">
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/Archivos/Fotos/<?php echo $candidatos[$count][$i+3]['Foto']; ?>" height="200" width="100%" />
                        </td>
                    </tr>
                    <tr>
                        <td class="col-md-2">
                            <label>
                                <?php if($candidatos[$count][$i]['IdCandidato']!=89){?>
                                <button onclick="loadList2(<?=$candidatos[$count][$i]['IdCandidato']?>, '<?=$candidatos[$count][$i]["Nombres"]?>')" class="btn" style="background-color:#006761;color:white;">Ver plancha</button><br>
                                <?php } ?>
                                <span style="font-size:1.2em;onclick="loadList2(<?=$candidatos[$count][$i+3]['IdCandidato']?>, '<?=$candidatos[$count][$i+3]["Nombres"]?>')" ">
                                    <?php echo $candidatos[$count][$i]['IdCandidato'] . " - " . $candidatos[$count][$i]['Nombres']; ?>
                                </span><br>
                                <input type="radio" value="<?php echo $candidatos[$count][$i]['IdCandidato']; ?>" name="candidato">
                            </label>
                        </td>
                        <td class="col-md-1 separateColumn"></td>
                        <td class="col-md-2">
                            <label>
                                <?php if($candidatos[$count][$i+1]['IdCandidato']!=89){?>
                                <button onclick="loadList2(<?=$candidatos[$count][$i+1]['IdCandidato']?>, '<?=$candidatos[$count][$i+1]["Nombres"]?>')" class="btn" style="background-color:#006761;color:white;">Ver plancha</button><br>
                                <?php } ?>
                                <span style="font-size:1.2em;">
                                    <?php echo $candidatos[$count][$i+1]['IdCandidato'] . " - " . $candidatos[$count][$i+1]['Nombres']; ?>
                                </span><br>
                                <input type="radio" value="<?php echo $candidatos[$count][$i+1]['IdCandidato']; ?>" name="candidato">
                            </label>
                        </td>
                        <td class="col-md-1 separateColumn"></td>
                        <td class="col-md-2">
                            <label>
                                <?php if($candidatos[$count][$i+2]['IdCandidato']!=89){?>
                                <button onclick="loadList2(<?=$candidatos[$count][$i+2]['IdCandidato']?>, '<?=$candidatos[$count][$i+2]["Nombres"]?>')" class="btn" style="background-color:#006761;color:white;">Ver plancha</button><br>
                                <?php } ?>
                                <span style="font-size:1.2em;">
                                    <?php echo $candidatos[$count][$i+2]['IdCandidato'] . " - " . $candidatos[$count][$i+2]['Nombres']; ?>
                                </span><br>
                                <input type="radio" value="<?php echo $candidatos[$count][$i+2]['IdCandidato']; ?>" name="candidato">
                            </label>
                        </td>
                        <td class="col-md-1 separateColumn"></td>
                        <td class="col-md-2">
                            <label>
                                <?php if($candidatos[$count][$i+3]['IdCandidato']!=89){?>
                                <button onclick="loadList2(<?=$candidatos[$count][$i+3]['IdCandidato']?>, '<?=$candidatos[$count][$i+3]["Nombres"]?>')" class="btn" style="background-color:#006761;color:white;">Ver plancha</button><br>
                                <?php } ?>
                                <span style="font-size:1.2em;">
                                    <?php echo $candidatos[$count][$i+3]['IdCandidato'] . " - " . $candidatos[$count][$i+3]['Nombres']; ?>
                                </span><br>
                                <input type="radio" value="<?php echo $candidatos[$count][$i+3]['IdCandidato']; ?>" name="candidato">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="separateRow"></td>
                    </tr>
                    <?php endif; ?>
                    <?php endfor; ?>
                </table>
                <?php $count++; ?>
                <?php endforeach; ?>
                <div style="text-align:center;padding-top:15px;">
                    <!--<button id="vote" class="btn-default-alt">
                        <img src="<?//php echo Yii::app()->request->baseUrl; ?>/images/votar.png" />
                    </button>-->
                    <button id="vote" style="background-color:#006761;color:white;" class="btn-lg">
                        &nbsp;&nbsp;Votar&nbsp;&nbsp;
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Plancha cantidado número</h4>
        </div>
        <div id="modal-body" class="modal-body">
            ...
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <!--<button type="button" class="btn btn-primary">Save changes</button>-->
        </div>
        </div>
    </div>
    </div>
    <?php $this->renderPartial('//mensajes/_alertConfirmation'); ?>
    <?php $this->renderPartial('//mensajes/_alertVerification'); ?>
</div>
<script>
function loadList2(id, nombre){

    if(id==89){
        return false;
    }else{
        $.ajax({
            data: {
                'id': id
            },
            url: 'index.php?r=Votacion/AjaxGetList',
            type: 'POST',
            success: function(response) {
                $("#modal-body").html(response);
            }
        }); 

        $("#myModalLabel").html("PLANCHA <strong style='color:#006761;'>#"+id+"</strong> ENCABEZADA POR <strong style='color:#006761;'>"+nombre+"</strong>");
        $('#myModal').modal('show');
    }
}
</script>