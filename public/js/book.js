jQuery(function($) {
    $("#create").on('click', function(event){
        event.preventDefault();
        var $stickynote = $(this);
        $.post("book/add", null,
            function(data){
                if(data.response == true){
                    $stickynote.before("<div class=\"book\"><textarea id=\"book-"+data.new_note_id+"\"></textarea><a href=\"#\" id=\"remove-"+data.new_note_id+"\"class=\"delete-book\">X</a></div>");
                    // print success message
                } else {
                    // print error message
                    console.log('could not add');
                }
            }, 'json');
    });

    $('#books').on('click', 'a.delete-book',function(event){
        event.preventDefault();
        var $stickynote = $(this);
        var remove_id = $(this).attr('id');
        remove_id = remove_id.replace("remove-","");

        $.post("book/remove", {
                id: remove_id
            },
            function(data){
                if(data.response == true)
                    $stickynote.parent().remove();
                else{
                    // print error message
                    console.log('could not remove ');
                }
            }, 'json');
    });

    $('#books').on('keyup', 'textarea', function(event){
        var $stickynote = $(this);
        var update_id = $stickynote.attr('id'),
            update_content = $stickynote.val();
        update_id = update_id.replace("book-","");

        $.post("book/update", {
            id: update_id,
            content: update_content
        },function(data){
            if(data.response == false){
                // print error message
                console.log('could not update');
            }
        }, 'json');

    });
});