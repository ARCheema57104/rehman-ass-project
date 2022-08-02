jQuery(document).ready( function($){
    $.ajax({
        url: reh_proj_object.ajax_url, 
        type: 'POST',
        data: { 
            action : 'get_reh_projects', 
            user: reh_proj_object.user,
            nonce: reh_proj_object.nonce,
        },
        success: function(data){
            console.log('success');
            console.log(data);
        },
        error: function (error) {
            console.log('errors');
            console.log(error);
        }
    });
});