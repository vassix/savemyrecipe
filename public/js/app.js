$(document).ready(function(){$("input").iCheck({checkboxClass:"icheckbox_flat-purple",radioClass:"iradio_flat-purple"}),$(".wysiwyg").summernote({placeholder:"Write here...",dialogsFade:!0,height:340,disableDragAndDrop:!0,toolbar:[["style",["bold","italic","underline","strikethrough"]],["para",["ul","ol","paragraph"]],["misc1",["link"]],["misc2",["undo","redo"]],["misc3",["fullscreen","codeview"]],["misc4",["help"]]]}),$(function(){$('[data-toggle="tooltip"]').tooltip(),$('[data-toggle="popover"]').popover()}),$("a.btn-ask-delete-confirm").on("click",function(e){var t=$(this);e.preventDefault(),swal({title:"Are you sure?",text:"You will not be able to recover this recipe!",type:"warning",showCancelButton:!0,cancelButtonText:"Close",confirmButtonColor:"#DD6B55",confirmButtonText:"Yes, delete it!",closeOnConfirm:!0},function(e){e&&(window.location=t.attr("href"),t.addClass("disabled"))})}),$("form input[type=submit]").click(function(){var e=$(this);e.button("loading"),setTimeout(function(){e.button("reset")},2e3)}),$("select:not(.no-select2)").select2({theme:"bootstrap",minimumResultsForSearch:6})});