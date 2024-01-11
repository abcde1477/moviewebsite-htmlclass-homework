var input=document.getElementById('search-field');   //输入框
input.onfocus=function(){
    if(this.value=="search")
        this.value="";
    this.style.color="black";
}
input.onblur=function(){
    if(this.value==""){
        this.value="search";
        this.style.color="#999"
    }
}
