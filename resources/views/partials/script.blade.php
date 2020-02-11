
<script >window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>
<script src="{{asset('js/app.js')}}"></script>  
<script src="{{asset('custom/js/jquery.min.js')}}"></script>
<script src="{{asset('custom/js/layui.all.js')}}" type="text/javascript" charset="utf-8"></script>
<script src="{{asset('custom/js/bootstrap.min.js')}}" type="text/javascript" charset="utf-8"></script>
<script src="{{asset('custom/js/owl.carousel.min.js')}}" type="text/javascript" charset="utf-8"></script>
<script src="{{asset('custom/js/index.js')}}" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" language="javascript">
    $(".ind_application").children().eq(0).show(); 
    $(".ind_solution").children().eq(0).addClass('layui-this')  
    function is_email(str) {
        if ((str.indexOf("@") == -1) || (str.indexOf(".") == -1)) {
            return false;
        }
        return true;
    }
    function CheckInputd(form) {
        
        form.fromurl.value=window.location.href;

         if (form.name.value == '') {
            alert("Please enter your name.");
            form.name.focus();
            return false;
        }
        if (!is_email(form.email.value)){
            alert("Please specify a valid email address.");
            form.email.focus();
            return false;
        }
        
        if (form.title.value == '') {
            alert("Please enter your messages.");
            form.title.focus();
            return false;
        }
        return true;
    } 
    $(".baiyun1").addClass('hover');

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 1 || document.documentElement.scrollTop > 1) {
    document.getElementById("postfix").style.display = "none";
  } else {
    document.getElementById("postfix").style.display = "block";
  }
}
</script>
@yield('script')