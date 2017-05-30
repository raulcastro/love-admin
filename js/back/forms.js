(function($){

	$.fn.SaiForm=function(opt){
		return this.each(SaiForm);
		
		function SaiForm(){
			var form=$(this);
			opt=$.extend({					
					okClass:'ok'
					,emptyClass:'empty'
					,invalidClass:'invalid'
					,successClass:'success'
					,onceVerifiedClass:'once-verified'
					,mailHandlerURL:'bat/MailHandler.php'					
					,successShowDelay:'4000'
					,stripHTML:true
				},opt);
				
			init();
			
			function init(){				
				form
					.on('submit',formSubmit)
					.on('reset',formReset)
					.on('focus','[data-constraints]',function(){
						$(this).parents('label').removeClass(opt.emptyClass);
					})
					.on('blur','[data-constraints]:not(.once-verified)',function(){
						$(this)
							.addClass(opt.onceVerifiedClass)
							.trigger('validate.form');
					})
					.on('keyup','[data-constraints].once-verified',function(){						
						$(this).trigger('validate.form');
					});
				
				if($('[data-constraints]',form).length!==0)
					$('[data-constraints]',form)
						.regula('bind')
						.on('show.placeholder',function(){
							fieldDesolation($(this));
						})
						.on('validate.form',fieldValidate);
				
				$('[placeholder]',form).SaiPlaceholder();
				
				$('[data-type=submit]',form)
				.click(function(){						
					form.trigger('submit');
					return false;
				});
				
				$('[data-type=reset]',form)
				.click(function(){						
					form.trigger('reset');
					return false;
				});
			};//Init End
			
			
			function fieldValidate(el){
				var el=$(this)
					,result=el.regula('validate')
					,isEmpty=false
					,isInvalid=false
					,isRequired=!!~el.data('constraints').indexOf('@Required');
				
				$.each(result,function(){
					if(this.constraintName==='Required')
						isEmpty=true;
					else
						isInvalid=true;
				});
				
				if(!el.hasClass(opt.onceVerifiedClass)&&!isEmpty)
					el.addClass(opt.onceVerifiedClass);
					
				if(isEmpty)
					el.parents('label').addClass(opt.emptyClass);
								
				if(isInvalid&&!isEmpty&&isRequired)
					el.parents('label')
						.removeClass(opt.emptyClass)
						.removeClass(opt.okClass)
						.addClass(opt.invalidClass);
						
				if(isInvalid&&!isRequired&&el.val())
					el.parents('label')
					.removeClass(opt.emptyClass)
					.removeClass(opt.okClass)
					.addClass(opt.invalidClass);
					
				if(!result.length)
					el.parents('label')
						.removeClass(opt.invalidClass)
						.removeClass(opt.emptyClass)
						.addClass(opt.okClass);
			};
			
			function fieldDesolation(el){
				el
					.removeClass(opt.onceVerifiedClass)
					.parents('label')
						.removeClass(opt.invalidClass)
						.removeClass(opt.emptyClass)
						.removeClass(opt.okClass);
			}
			
			function getValue(el){
				return el.val()||false;
			};
			
			function formSubmit(){
				$('[data-constraints]',form).trigger('validate.form');
				
				if(!$('label.'+opt.invalidClass+',label.'+opt.emptyClass,form).length){					
					$.ajax({
						type:"POST"
						,url:opt.mailHandlerURL
						,data:{
							name:getValue($('label.name input'))
							,email:getValue($('label.email input'))
							,phone:getValue($('label.phone input'))
							,fax:getValue($('label.fax input'))
							,state:getValue($('label.state input'))
							,message:getValue($('label.message textarea'))
							,owner_email:opt.ownerEmail||'#'
							,stripHTML:opt.stripHTML
						}
						,success: function(e){							
							form.addClass(opt.successClass);
							setTimeout(function(){
								form
								.removeClass(opt.successClass)
								.trigger('reset');
							},opt.successShowDelay);
						}
					});			
				}
				
				return false;
			}
			
			function formReset(){
				fieldDesolation($('[data-constraints]',form));			
			}
			
		}//SaiForm Ends
	};
})(jQuery);

;(function($){
	$.fn.SaiPlaceholder=function(opt){
		return this.each(function(){
			var th=$(this)
				,placeholder_text
				,placeholder;
						
			opt=$.extend({
					placeholderClass:'_placeholder'
					,placeholderFocusedClass:'focused'
					,placeholderHiddenClass:'hidden'
				},opt);
				
			init();
			
			function init(){
				placeholder_text=th.attr('placeholder');
				placeholder=$(document.createElement('span'));
				placeholder
					.addClass(opt.placeholderClass)
					.css({				
						left:th.prop('offsetLeft')
						,top:th.prop('offsetTop')
						,width:th.width()
						,height:th.outerHeight()				
					})
					.text(placeholder_text)
					.appendTo(th.parent())				
					.click(function(){
						th.focus();
						return false;
					})
					.on('contextmenu',function(){						
						th.trigger('hide.placeholder').focus();					
					});
				
				th
					.val('')
					.removeAttr('placeholder')
					.on('hide.placeholder',function(){
						placeholder.addClass(opt.placeholderHiddenClass);
					})
					.on('show.placeholder',function(){
						placeholder.removeClass(opt.placeholderHiddenClass);
					})
					.on('focus',function(){
						placeholder.addClass(opt.placeholderFocusedClass);
					})
					.on('blur',function(){
						var val=th.val();
						if(val===''||val===placeholder_text)
							th.val('')							
							,th.trigger('show.placeholder');
						placeholder.removeClass(opt.placeholderFocusedClass);
					})
					.on('keydown',function(e){												
						if(e.keyCode===32||e.keyCode>46)
							th.trigger('hide.placeholder');					
					})
					.on('keyup',function(){						
						if(th.val()===''){							
							th.trigger('show.placeholder');
							return false;
						}else{							
							th.trigger('hide.placeholder');
						}
					})
					.parents('form').on('reset',function(){
						th.trigger('show.placeholder');				
					});
			}			
		});
	};
})(jQuery);

$(window).load(function(){
    $('#contact-form').SaiForm({
        ownerEmail:'my@email.com' 
    });

});