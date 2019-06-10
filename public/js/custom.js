$(window).on('load',function () {
    $('a[title=Presentar]').on('click',function(event){
        event.preventDefault();
        $('#presentar_form')[0].action = $('#presentar_form')[0].action +"/presentar/"+this.id;
        $('#presentar_modal').modal('show');
    });
})
