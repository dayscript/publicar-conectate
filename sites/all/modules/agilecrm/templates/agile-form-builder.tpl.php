<style>
.modalDialog {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.8);
    z-index: 99999;
    opacity:0;
    -webkit-transition: opacity 400ms ease-in;
    -moz-transition: opacity 400ms ease-in;
    transition: opacity 400ms ease-in;
    pointer-events: none;
}
.modalDialog:target {
    opacity:1;
    pointer-events: auto;
}
.modalDialog > div {
    width: 400px;
    position: relative;
    margin: 3% auto;
    padding: 5px 22px 13px 20px;
    background: #fff;
    height: 500px;
    z-index: 100050;
    -webkit-box-shadow: 0 3px 6px rgba( 0, 0, 0, 0.3 );
    box-shadow: 0 3px 6px rgba( 0, 0, 0, 0.3 );
    overflow-x: scroll;

}
.close {
    background: #606061;
    color: #FFFFFF;
    line-height: 25px;
    position: absolute;
    right: 3px;
    text-align: center;
    top: 4px;
    width: 24px;
    text-decoration: none;
    font-weight: bold;
    -webkit-border-radius: 12px;
    -moz-border-radius: 12px;
    border-radius: 12px;
    -moz-box-shadow: 1px 1px 3px #000;
    -webkit-box-shadow: 1px 1px 3px #000;
    box-shadow: 1px 1px 3px #000;
}
.close:hover {
    background: #00d9ff;
}
</style>
<?php
foreach ($output as $value) {
      $domain = $value->setting_domain;
      $email = $value->setting_email;
      $password = $value->setting_password;
       $restapkey = $value->setting_restapikey;
       $jsapikey = $value->setting_jsapikey;
 }

 
?>
<div id="features">
<div class="mainLeftbox">
<div id="my-content-id_fombuilders">
<h3 class="font-thin h3">Templates</h3>
                  <div class="landing-icon"><div class="langing-img"> <img src="https://<?php echo $domain; ?>.agilecrm.com/misc/formbuilder/templates/bootstrap/bootstrap-subscribenow-thumbnail.png" width="87%"> <div>
            <a class="btn btn-sm btn-default landingPageTemplateSelect" href="#" onclick="document.getElementById('agile-top-button-subscribenow').click();return false;" target="_blank">Go</a>
             
        </div></div></div>
                         <div class="landing-icon"><div class="langing-img"> <img src="https://<?php echo $domain; ?>.agilecrm.com/misc/formbuilder/templates/bootstrap/bootstrap-scheduledemo-thumbnail.png" width="87%"> <div>
            <a class="btn btn-sm btn-default landingPageTemplateSelect" href="#" onclick="document.getElementById('agile-top-button-scheduledemo').click();return false;" target="_blank">Go</a>
             
        </div></div></div>
                         <div class="landing-icon"><div class="langing-img"> <img src="https://<?php echo $domain; ?>.agilecrm.com//misc/formbuilder/templates/bootstrap/bootstrap-downloadebook-thumbnail.png" width="87%"> <div>
            <a class="btn btn-sm btn-default landingPageTemplateSelect" href="#" onclick="document.getElementById('agile-top-button-downloadebook').click();return false;"  target="_blank">Go</a>
             
        </div></div></div>
                         <div class="landing-icon"><div class="langing-img"> <img src="https://<?php echo $domain; ?>.agilecrm.com/misc/formbuilder/templates/bootstrap/bootstrap-membership-thumbnail.png" width="87%"> <div>
            <a class="btn btn-sm btn-default landingPageTemplateSelect" href="#" onclick="document.getElementById('agile-top-button-membership').click();return false;"  target="_blank">Go</a>
             
        </div></div></div>
                         <div class="landing-icon"><div class="langing-img"> <img src="https://<?php echo $domain; ?>.agilecrm.com/misc/formbuilder/templates/bootstrap/bootstrap-testimonial-thumbnail.png" width="87%"> <div>
            <a class="btn btn-sm btn-default landingPageTemplateSelect" href="#" onclick="document.getElementById('agile-top-button-testimonial').click();return false;"  target="_blank">Go</a>
             
        </div></div></div>
                         <div class="landing-icon"><div class="langing-img"> <img src="https://<?php echo $domain; ?>.agilecrm.com/misc/formbuilder/templates/metro/metro-subscribenow-thumbnail.png" width="87%"> <div>
            <a class="btn btn-sm btn-default landingPageTemplateSelect" href="#" onclick="document.getElementById('agile-top-button-subscribenow').click();return false;"  target="_blank">Go</a>
             
        </div></div></div>
          </div>
<div class="crm-form-list" id="crmj">        
 <div class="formbilder">
    <div class="add-forms">
      <a class='more' onclick="document.getElementById('agile-top-button-send').click();return false;" target='_blank' href="#">Create Form Builder</a>
             <a onclick="window.location.reload();" title="Refresh" class="reload more">↻</a></div></div>
                         <div class="formbilder">
               <table class="wp-list-table widefat plugins" id="form_builder_list">
                   <thead>
                     <tr><th scope="col" id="name" class="manage-column column-name column-primary">S.No</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                       <th scope="col" id="name" class="manage-column column-description">Perview</th>  
                     </tr>
                   </thead>
                    <tbody id="the-list">
                   <?php 
                    $i= 1;
                    if($form_output){
                   foreach($form_output as $v){ 
                    
                   echo "<tr><th><strong>".$i.".</strong></th><th>".$v->formName."</th>"; ?>
                   <div id="my-content-id_<?php echo $i ?>" class="modalDialog">
                        <div> <a href="#close" title="Close" class="close">X</a>

                             
                          <?php echo  $v->formHtml; ?>
                            
                        </div>
                    </div>
                  <th><a href="#my-content-id_<?php echo $i ?>" class="thickbox" id="preview">
                  <img src="../../sites/all/modules/agilecrm/images/preview.png" title='Preview' width='20px'  height='20px'> </a> 
                  
                  
                  </th>
                  </tr>
                  <?php $i++; } } else{ ?>
                  <tr><th id='count' colspan='3'>Sorry, you dont have any Forms yet.</th></tr>
                  <?php  
                  
               }
               ?>
               </tbody>
                   <!-- <tbody id="the-list">
                   {% if test_var %}
                     {% for item in test_var %}
            		   <tr>
            		     <th>{{ loop.index }}</th>
                         <th>{{item.formName}}</th>
                         <div id="openModal_{{ loop.index }}" class="modalDialog">
                        <div> <a href="#close" title="Close" class="close">X</a>

                             
                         <iframe width="100%" height="100%" src="https://<?php echo $domain; ?>.agilecrm.com/forms/{{ item.id }}" frameborder="0"></iframe>
                            
                        </div>
                    </div>
                         <th><a href="#openModal_{{ loop.index }}" class="thickbox" id="preview"><img src="../../sites/all/modules/agilecrm/images/preview.png"></a></th>
                       </tr>
					{% endfor %}
                    {% else %}
                        <tr><th id='count' colspan='3'>Sorry, you dont have any Forms yet.</th></tr>
                    {% endif %}
                  </tbody> -->
                 </table>
                 </div>
               </div>
            </div>
<div class="mainrightbox">
  <div class="box-right">  
  <div id="my-content-id_webrules">
<h3 class="font-thin h3">Form Builder</h3>
<p>Form Builder helps you create your custom web forms easily, and you can place it on your website. Whenever a web visitor fills up the web form, a new contact gets created in Agile CRM, and all the data submitted through the form gets added to the contact page as various attributes </p>
<img src="../../sites/all/modules/agilecrm/images/frombuilder.png" class="contentimage" title="Form Builder" width="95%"><p> Name, Company, Phone number, Email, Address, Notes etc. Also, you can keep tracking this contact whenever he visits your website and get his detailed browsing history on the contact page.</p>
</div>      
      <h3 class="m-t-none h4 m-b-sm">What are Forms?</h3>
      <p>
      Forms created using the Form Builder can be placed on your website or app. These Forms are readily linked to your Agile account.  When a visitor fills the form, a Contact is created and subsequent web activity is logged automatically.</p>
       <p>
        <iframe width="100%" height="180px" src="https://www.youtube.com/embed/jarxzsC_R0g" frameborder="0" allowfullscreen="" class="wp-campaigns-video"></iframe>
     </p>
      <p>Agile's Form Builder helps you create your custom web forms at ease and place it on your website. Whenever a web visitor fills up the web form, a new contact gets created in Agile and all the data submitted through the form gets added to the contact page as various attributes - Name, Company, Email, Phone no, Address, Notes etc, Also, you can keep tracking this contact whenever he visits your website &amp; get his detailed browsing history on the contact page.</p>
      <a href="https://www.agilecrm.com/web-engagement" target="_blank" class="fb-read">Read more</a>
     </div>

 </div>
</div>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=bootstrap/bootstrap-subscribenow-index.json" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-subscribenow" class="saveBtn" required="" type="submit" name="logind" value="Logind">
   </form>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=bootstrap/bootstrap-scheduledemo-index.json" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-scheduledemo" class="saveBtn" required="" type="submit" name="logind" value="Logind">
   </form>
   <form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=bootstrap/bootstrap-downloadebook-index.json" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-downloadebook" class="saveBtn" required="" type="submit" name="logind" value="Logind">
   </form>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=bootstrap/bootstrap-membership-index.json" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-membership" class="saveBtn" required="" type="submit" name="logind" value="Logind">
   </form>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=bootstrap/bootstrap-testimonial-index.json" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-testimonial" class="saveBtn" required="" type="submit" name="logind" value="Logind">
   </form>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=metro/metro-subscribenow-index.json" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-theme2" class="saveBtn" required="" type="submit" name="logind" value="Logind">
</form>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#forms" method="post" target="_blank" style="display: none;">
<input type="text" required="" name="email" value="<?php echo $email; ?>">
<input required="" type="password" name="password" value="<?php echo $password; ?>">
<input required="" type="hidden" name="type" value="agile">
<input id="agile-top-button-send" class="saveBtn" required="" type="submit" name="login" value="Login">
</form>



<script src="https://pubnub.a.ssl.fastly.net/pubnub-3.4.min.js" ></script>
<script>
  // CREATE A PUBNUB OBJECT
  Agile_Pubnub = PUBNUB.init({ 'publish_key' : 'pub-c-e4c8fdc2-40b1-443d-8bb0-2a9c8facd274', 'subscribe_key' : 'sub-c-118f8482-92c3-11e2-9b69-12313f022c90',
      ssl : true, origin : 'pubsub.pubnub.com', });
  Agile_Pubnub.subscribe({ channel : getAgileChannelName(), restore : false, message : function(message, env, channel)
{
    console.log(message);
    var action = message.action;
    var name = message.type;
    if(name== 'Forms'){
      window.location.reload();
    }
}});
 function getAgileChannelName(){  
     return  "<?php echo $restapkey ?>";
    }
</script>
