$(document).ready(function(){
    $("#modalEditUser").on("show.bs.modal", function(event){
        // Get the button that triggered the modal
        var button = $(event.relatedTarget);

        // Extract value from the custom data-* attribute
        var titleData = button.data("title");
        $(this).find(".modal-title").text(titleData);
    });
});
