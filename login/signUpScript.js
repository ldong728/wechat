/**
 * Created by godlee on 2015/4/1.
 */
$(document).ready(function () {
    $('#name').change(function(){
        $('#usernameInf').empty();
        $.post('ajax.php',{checkName:$('#name').val()},function($number){
            if($number==0){
                $('#usernameInf').append('用户名可用');
                $('#nameReady').val('1');
            }else{
                $('#usernameInf').append('用户名已存在，请更换用户名');
                //$('#usernameInf').append('返回的：'+$number);
                $('#nameReady').val('0');
            }
        });
    });
    $('#password2').change(function(){
        $('#passwordInf').empty();
        if($('#password2').val()==$('#password1').val()){
            $('#passwordInf').append('密码一致');
        }else{
            $('#passwordInf').append('密码不一致，请检查输入');
        }
    });
});
