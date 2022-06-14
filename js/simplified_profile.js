/**
 * Clear the input of this block
 * @param instanceId      | id of the block
 */
function simplifiedProfile_clearAll(instanceId) 
{
    $('#inst'+instanceId+' input').val('');
}

/**
 * Update the user with the data 
 * @param instanceId      | id of the block
 */
function simplifiedProfile_updateUser(instanceId) 
{
    var currentPassword = $('#inst'+instanceId+' input[name="current_password"]').val();
    var newPassword = $('#inst'+instanceId+' input[name="new_password"]').val();
    var newPasswordConfirm = $('#inst'+instanceId+' input[name="new_password_confirm"]').val();
    
    $.ajax({
        method: "POST",
        url: M.cfg.wwwroot + "/blocks/simplified_profile/block_simplified_profile_api.php",
        data: {instanceId : instanceId,
               newPasswordConfirm: newPasswordConfirm,
               newPassword: newPassword,
               currentPassword: currentPassword
        }
    }).done(function(data) {
        $('#inst'+instanceId+' .error_message').html('');
        if (data.error != undefined) {
            $('#inst'+instanceId+' .error_message.'+data.error_location).html(data.error);
        }
        if (data.successMessage != undefined) {
            simplifiedProfile_clearAll(instanceId);
            $('#inst'+instanceId+' .success-message').html('<div class="alert alert-success" role="alert">'+data.successMessage+'<button type="button" class="close" data-dismiss="alert">×</button></div>');
        }
    });
}

