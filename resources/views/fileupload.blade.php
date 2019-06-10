@extends('layouts.app')
@section('css')
    <style>
        .file-button{
            margin-left:5px;
            border:solid 1px #007bff;
            border-radius:5px;
            background-color:transparent;
            color: #007bff;
            vertical-align:middle;
            display:block !important;
        }
        .file-link{
            display:block !important;
        }
        .item{
            display:flex !important;
            flex-direction:row;
            border:solid 1px lightgrey;
            border-radius:5px;
            margin-bottom:5px;
            padding:5px;
        }
    </style>
@endsection
@section('content')
<div class="container">
    <form action="{{route('prueba.single')}}" method="POST">
        @csrf
        <div class="container-fluid card justify-content-center" >    
            <div class="row card-header">
                Carga de 1 solo archivo
            </div>
            <div class="row card-body">
                <div class="col-md-6">hola</div>
                <div class="col-md-6"><input type="file" data-name="single" name="single"></div>
            </div>
            <div class="row card-header">
                Carga de multiple de archivos
            </div>
            <div class="row card-body">
                <div class="col-md-6">hola</div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6"><input type="file" multiple data-name="multiple" name="multiple[]"></div>
                        <div class="col-md-6">
                            <h5 style="text-align:center">Archivos Cargados</h5>
                            <ul id="loaded"></ul>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
        <input type="submit">
    </form>
</div>
<script src="/js/app.js" type="text/javascript"></script>
@endsection
@section('javascript')
    <script type="text/javascript">
        function listHandler () {
            var children = Array.from($('#loaded').children());
            var index = children.indexOf($(event.target).parent()[0]);
            children.splice(index,1);
            $('#loaded').html("");
            $('#loaded').append(children);
            return index;
        }
        function removeInvalid (ex,files){
            let valid = [];
            if (Array.isArray(ex)){
                for (let ctr = 0; ctr<files.length; ctr++){
                    if (ex.indexOf(ctr) === -1)
                        valid.push(files[ctr]);
                }
                return valid;
            }
            files.splice(ex,1);
            return files;
        }
        function validFileHandler (index,fileArray) {
            let transfer = new DataTransfer();
            fileArray = removeInvalid(index,fileArray);
            for (let k=0; k<fileArray.length ; k++){
                transfer.items.add(fileArray[k]);
            }
            return transfer.files;
        }
        function deleteFile(event){
            event.preventDefault();
            var removedIndex = listHandler();
            $('input[multiple]')[0].files = validFileHandler(removedIndex,Array.from($('input[multiple]')[0].files));
        }
        function excludedIndex(files){
            let excluded = [];
            for (let ctr = 0; ctr<files.length; ctr++){
                var ex = files[ctr].name.split('.').pop();
                var accepted = ['docx','doc','pdf'];
                if ( accepted.indexOf(ex) === -1 ){
                    excluded.push(ctr);
                }
            }
            return excluded;
        }
        function checkAcceptedExtension (files){
            let excluded = excludedIndex (files);
            let accepted = [];
            if (excluded.length > 0){
                files = validFileHandler(excluded, files);
                $('input[multiple]')[0].files = files;
            }
            return Array.from(files);
        }
        $(window).on('load',function(){
            $('input[multiple]').on('change',function(){
                if (this.files.length <= 5){
                    if($('#loaded').html())
                    {
                        $('#loaded').html("");
                    }
                    var fileArray = checkAcceptedExtension(Array.from(this.files)); 
                    for (var ind = 0; ind<fileArray.length; ind++)
                    {
                        var reader = new FileReader();
                        reader.onload = (function (file,index) {
                            return function (e){
                                var info = e.target.result;
                                $("#loaded").append("<li class='item'><a class='fileLink' download='"+file.name+"' target='_blank' href='"+info+"'>"+file.name+"</a><button id ='file_"+index+"' onclick='deleteFile(event)' class='file-button'>x</button></li>");
                            }
                        })(fileArray[ind],ind);
                        reader.readAsDataURL(fileArray[ind]);
                    }
                } else {
                    this.value = "";
                    //activar modal con advertencia.
                    alert('solo puedes cargar hasta 5 archivos');
                } 
            });
        });
    </script>
@endsection