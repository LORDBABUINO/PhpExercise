<?php
/*  SÍNTESE:
 *
 *      Objetivo:   Atualizar limites monetários destinados à limpeza.
 *      Entradas:   dateStart, dateEnd, state, ordinance, validity, limit[4][2].
 *      Saídas:     dateStart, dateEnd, state, ordinance, validity, limit[4][2].
 */

// Verifica a existência das váriaveis e as declara com condições.                
$dateStart = filter_input(INPUT_POST, 'dateStart');
$dateEnd = filter_input(INPUT_POST, 'dateEnd');
$state = filter_input(INPUT_POST, 'state');
$ordinance = filter_input(INPUT_POST, 'ordinance');
$validity = filter_input(INPUT_POST, 'validity', FILTER_VALIDATE_BOOLEAN);
$limit = [[.0, .0],[.0, .0],[.0, .0],[.0, .0]];

for ($local = 0; $local < 4; $local++) {
    for ($minMax = 0; $minMax < 2; $minMax++) {
        $name = 'limit' . $local . $minMax;
        $limit[$local][$minMax] = filter_input(INPUT_POST, $name, FILTER_VALIDATE_FLOAT);
    }
}
$publicar = isset($_POST['publicar']) ? $_POST['publicar'] : '';

//**********************************************************************************************************************    
// Insere dados do arquivo no banco de dados e o transfere o arquivo para a pasta indicada.
if ($publicar == 'Publicar') {
    $dateStart = $cls_dts->dt_bd($dateStart);
    $dateEnd = $cls_dts->dt_bd($dateEnd);
    $dataupdate = date("Y-m-d", strtotime($dateStart . "- 1 day"));

    $sql_update = "UPDATE plan_limites_limp SET VIG_FIM = '" . $dataupdate . "', VIGENTE = 'NÃO' WHERE UF = '" . $state . "' AND VIG_FIM = '0000-00-00';";

    $sql_ins_ = "INSERT INTO plan_limites_limp (UF, PORTARIA, VIG_INI, VIG_FIM, VIGENTE, INTERNA_MIN, INTERNA_MAX, EXTERNA_MIN, EXTERNA_MAX, ESQUADRIA_MIN, ESQUADRIA_MAX, FACHADA_MIN, FACHADA_MAX)
									VALUES ('" . $state . "', '" . $ordinance . "', '" . $dateStart . "',
											'" . $dateEnd . "','" . $validity . "','" . $internoMin . "',
											'" . $internoMax . "','" . $externoMin . "','" . $externoMax . "',
											'" . $esquadriaMin . "','" . $esquadriaMax . "','" . $fachadaMin . "',
											'" . $fachadaMax . "')";

    // Estância as funções de acesso ao banco e executa a query.
    $upd_ok = class_bd::cnx()->query($sql_update);
    $ins_ok = class_bd::cnx()->query($sql_ins_);

    // Verifica se a query foi executada corretamente, se não, executa a mensagem de falha.   
    // Se sim, Confirma o Cadastro e Redireciona a página.
    if ($ins_ok == '1' && $upd_ok == '1') {
        echo "<script> alert('Cadastro realizado com sucesso.')</script>";
        echo "<script>location.href=('../../centro.php')</script>";
        exit;
    } else {
        echo "<script> alert('Falha ao Cadastrar entre em contato com o NUINF no 3212 8634!')</script>";
    }
}

//********************************************************************************************************************** 
// Forma o HEAD cria um array dos arquivos a serem Incluidos de JS. 
// Forma as propriedades do Body.  
$cls_htm->HTMLSTART();
$arqvs = ARRAY('../includes/js/jquery-3.2.1.min.js', '../includes/js/jquery.mask.js', '../includes/js/camadas.js', '../includes/js/format_valid.js', "../includes/js/forms_moeda.js");
$cls_htm->HEADSTART("../includes/css/_intra_aplicat.css", $arqvs);
$cls_htm->BODYSTART("0", "", "");

//**********************************************************************************************************************   
?> 

<form action="publica_limites_limp.php" method="POST" name="frms" onSubmit="return valid_frms(this);">           

    <table position="fixed" width="100%" border="0" cellpadding="2" cellspacing="2" onMouseOut='MM_swapImgRestore()' onMouseOver="MM_swapImage('aba_ofc', '', '../../includes/imgs/aba_az.png', 1)">
        <tr>
            <td width="1%" valign='top'>
                <img name='aba_ofc' src="../../includes/imgs/aba_cen.png" align="absmiddle" width=1 height=700> 
            </td> 
            <td align="center" valign='top'> 

                <table Align=center cellpadding='0' cellspacing='0' width='80%'>
                    <tr>
                        <td height="80" width="100%" class='ttlo' valign='top'>
                            <img src="../includes/imgs/ico_email.png" align="absmiddle">
                            PUBLICAÇÃO DE LIMITES - LIMPEZA (R$/m²)
                        </td>        
                    </tr> 
                    <TR>
                        <td valign="top" width='100%' align="left">
                            <!-- /******************************************************************************************************* -->
                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
                                <TR>
                                    <TD width=30>&nbsp;</TD>
                                    <TD width=15 height=10><IMG src='../includes/imgs/up-left.gif' border=0></TD>
                                    <TD><IMG height=15 src='../includes/imgs/up.gif' width='100%'></TD>
                                    <TD><IMG height=15 src='../includes/imgs/up-right.gif' width=15 border=0></TD>
                                </TR>
                                <TR>
                                    <TD width=30>&nbsp;</TD>
                                    <TD width=10 background='../includes/imgs/left.gif'>&nbsp;</TD>
                                    <td width='100%' vAlign=top align="center">
                                        <!-- /******************************************************************************************************* -->        
                                        <TABLE width="100%" border="0" align="center" cellSpacing=2 cellPadding=2>
                                            <tr valign="top" >
                                                <td>

                                                    <TABLE width="100%" border="0" align="center" cellSpacing=2 cellPadding=2>     
                                                        <tr height='60' valign="top" >
                                                            <td colspan="8">
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td>
                                                                            <b>Data de início:</b><br>
                                                                            <input id="dateStart" name="dateStart" value='<?php echo $dateStart; ?>' type="text" size="12" minlength="10" class='frms_text data' onBlur='foco(this)' onfocus='foco(this)'><br>
                                                                        </td>
                                                                        <td>
                                                                            <b>Data de término:</b><br>
                                                                            <input id="dateEnd" name="dateEnd" value='<?php echo $dateEnd; ?>' type="text" size="12" minlength="10" class='frms_text data' onBlur='foco(this)' onfocus='foco(this)' opcional='sim'><br>
                                                                        </td>
                                                                        <td>
                                                                            <b>UF:</b><br>
                                                                            <select id='state' name='state' class='frms_select' style='width: 95px;' onBlur='foco_select(this)' onfocus='foco_select(this)'>
                                                                                <option value=''>UF</option>
                                                                                <?php
                                                                                $arrayEstado = array(
                                                                                    "AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO",
                                                                                    "MA", "MG", "MS", "MT", "PA", "PB", "PE", "PI", "PR",
                                                                                    "RJ", "RN", "RO", "RR", "RS", "SC", "SE", "SP", "TO"
                                                                                );
                                                                                for ($i = 0; $i < 27; $i++) {
                                                                                    echo("<option value=" . $arrayEstado[$i] . ">" . $arrayEstado[$i] . "</option>");
                                                                                }
                                                                                ?> 
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <b>Portarias SLTI-MPOG N°:</b><br>
                                                                            <input id="ordinance" name="ordinance" value='<?php echo $ordinance; ?>' type="text" size="12" minlength="7" class='frms_text' onBlur='foco(this)' onfocus='foco(this)'><br>
                                                                        </td>
                                                                        <td>
                                                                            <b>Vigente?</b><br>
                                                                            <input id="validity" name="validity" value='<?php echo $validity; ?>' type="text" size="12" class='frms_text'><br>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>  
                                                        <tr id="titulos" height='60' valign="top">
                                                            <td colspan="2">
                                                                <h3>Área Interna</h3>
                                                            </td>
                                                            <td colspan="2">
                                                                <h3>Área Externa</h3>
                                                            </td>
                                                            <td colspan="2">
                                                                <h3>Esquadria Externa</h3>
                                                            </td>
                                                            <td colspan="2">
                                                                <h3>Fachada Envidraçada</h3>
                                                            </td>                   
                                                        </tr>     
                                                        <tr id="campos" height='60' valign="top">
                                                            <?php
                                                            for ($local = 0; $local < 4; $local++) {
                                                                for ($minMax = 0; $minMax < 2; $minMax++) {
                                                                    $name = 'limit' . $local . $minMax;
                                                                    echo('<td>');
                                                                    if ($minMax) {
                                                                        echo('<b>Valor máximo:</b><br>');
                                                                        echo('<input name=' . $name . ' type="text" size="10" maxlength="16" class="frms_text" onkeyup="this.value = mascara_moeda("[###.]###,##", this.value)><br>');
                                                                    } else {
                                                                        echo('<b>Valor mínimo:</b><br>');
                                                                        echo('<input name=' . $name . ' type="text" size="10" maxlength="16" class="frms_text" onkeyup="this.value = mascara_moeda("[###.]###,##", this.value)><br>');
                                                                    }
                                                                    echo('</td>');
                                                                }
                                                            }
                                                            ?>                 
                                                        </tr>

                                                        <tr>
                                                            <td height='30' valign="bottom" colspan='8' align='center' style="padding-top: 10px">
                                                                <input type='submit' name='publicar' value='Publicar' class='frms_submit'>
                                                                <input type='button' id='voltar' value='Voltar' class='frms_submit'>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr> 
                                            <tr>

                                                <td height="40" width='100%' valign="bottom" colspan='3'>
                                                    &nbsp;&nbsp;<b><i>Data e hora de transferência:</i></b> <font color='#FF0000'><?php echo $cls_dts->dt_hr_hj_usu(); ?></font>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;<i><b>Responsável pela transferência:</b></i><font color="#FF0000"> <?php echo $nome; ?></font><BR>
                                                </td>
                                            </tr>                  
                                        </TABLE>
                                        <!-- /******************************************************************************************************* -->  

                                    </td>
                                    <TD background='../includes/imgs/right.gif'>&nbsp;</TD>
                                </TR>
                                <TR>
                                    <TD width=30>&nbsp;</TD>
                                    <TD width=15 height=10><IMG src='../includes/imgs/down-left.gif' border=0></TD>
                                    <TD><IMG height=15 src='../includes/imgs/down.gif' width='100%'></TD>
                                    <TD><IMG height=15 src='../includes/imgs/down-right.gif' width=15 border=0></TD>
                                </TR>
                            </table> 
                            <!-- /******************************************************************************************************* -->
                        </TD>

                    </TR> 
                </table>
            </td>
        </tr>
    </table>    
</form>
<style>
    td{
        text-align: center;
    }
    #titulos td, #campos td{
        border: 1px solid black;
    }    
</style>
<script>

    $(".data").mask("99/99/9999");
    $("#ordinance").mask("99/9999");

    $("#voltar").click(function () {
        history.back();
    });

    $("#dateEnd").blur(function () {

        var dateEnd = parseDate($("#dateEnd").val());
        var hoje = new Date();

        if (dateEnd > hoje || !dateEnd)
            $("#validity").attr("value", "SIM");
        else
            $("#validity").attr("value", "NÃO");
    });

    function parseDate(data) {
        data = data.split("/");
        var dia = parseInt(data[0], 10);
        var mes = parseInt(data[1], 10);
        var ano = parseInt(data[2], 10);
        data = new Date();
        return data.setFullYear(ano, mes - 1, dia);
    }

</script>