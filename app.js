window.addEventListener('DOMContentLoaded',function(){
   var imgArea = document.getElementById('imgArea');
   var imgFile = document.getElementById('imgFile');
   imgArea.addEventListener('dragover',function(e){
        e.stopPropagation();
        e.preventDefault();
        imgArea.style.border = "1px dashed black";
   });

    imgArea.addEventListener('dragleave',function(e){
       e.stopPropagation();
       e.preventDefault();
       imgArea.style.border = "none";
    });

    imgFile.addEventListener('change',function(e){
       imgArea.style.border = "none";
       var file = this.files[0];
       var img = imgArea.firstElementChild;
       var FileReader = new window.FileReader();

       FileReader.onload = function(event){
           img.setAttribute('src',event.target.result);
           imgFile.classList.add('form__file--after');
           img.classList.remove('prev__img--before')
       };
       FileReader.readAsDataURL(file);
    });
});