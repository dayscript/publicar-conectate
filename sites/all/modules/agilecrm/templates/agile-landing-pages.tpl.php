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
<div id="my-content-id_landing">
<h3 class="font-thin h3">Template</h3>
<div class=""><div class="langing-img">
<img src="../../sites/all/modules/agilecrm/images/landingpage_main.png" title="Preview" width="20px" height="20px"> <div>
    <a class="btn btn-sm btn-default landingPageTemplateSelect" onclick="document.getElementById('agile-top-button-template2').click();return false;" href="#">Go</a>
        </div></div></div>
</div>
<div class="crm-form-list">             
<div class="formbilder">
<div class="add-forms">
<a class="more" onclick="document.getElementById('agile-top-button-send').click();return false;" target="_blank" href="#">Create Landing Pages</a>
             <a onclick="window.location.reload();" title="Refresh" class="reload more">↻</a></div></div>
                         <div class="formbilder">
            <table class="wp-list-table widefat plugins" id="form_builder_list">
                   <thead>
                     <tr><th scope="col" id="name" class="manage-column column-name column-primary">S.No.</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                       <th scope="col" id="name" class="manage-column column-description">Preview</th>  
                     </tr>
                   </thead>
                    <tbody id="the-list">
                 
                     <?php 
                    $i= 1;
                    if($form_output) {
                   foreach($form_output as $v){ 
                   
                   echo "<tr><th><strong>".$i.".</strong></th><th>".$v->name."</th>"; ?>
                   <th><?php echo "<a target='_blank'  id='preview' href='https://".$domain.".agilecrm.com/landing/".$v->id."'> <img src='../../sites/all/modules/agilecrm/images/preview.png' title='Preview' width='20px' height='20px'/></a>"; ?> </th>
                  </tr>
                  <?php $i++; } }
                 else{
               ?>
                  <tr ><th id='count' colspan='3'>Sorry, you dont have any Landing Pages yet.</th></tr>
                   <?php  
                    } ?>
                  </tbody>
                 </table>
                 </div>
              </div>
</div>
<div class="mainrightbox">
  <div class="box-right">   
  <div id="my-content-id_webrules">
<h3 class="font-thin h3">Landing Page</h3>
<p>Landing pages are your lead magnet - a web page created to gather leads online. Create a landing page in Agile CRM and link it from your website, email messages or online ads.</p>
 <img src="../../sites/all/modules/agilecrm/images/landing.png" class="contentimage" title="Landing Page" width="95%"> 
 <p>Add a form to your landing page to gather visitor details, create contacts in Agile CRM automatically, and nurture them using campaigns.</p>
</div>     
            <h3 class="m-t-none h4 m-b-sm">How to use Landing Pages?</h3>
      <div>
      Landing page is your lead magnet - a web page created to gather leads online. Create a landing page in Agile and link it from your website, email messages or online ads. Add a Form to your landing page to gather visitor details, create Contacts in Agile automatically and nature them using Campaigns.
      </div>
       <p>
        <iframe width="100%" height="180px" class="embed-responsive-item wp-campaigns-video" src="https://www.youtube.com/embed/iVTvUoEXTKY" frameborder="0" allowfullscreen=""></iframe>
     </p>
      <h4 class="m-t-none h4 m-b-sm">What are Landing Pages?</h4>
     <p>
      The Landing Page Builder helps create high converting landing pages in Agile CRM. With Agile's rich and customizable templates, drag &amp; drop designer features, web forms, responsive designs and code editor, experience a new level in building high quality landing pages.</p>
      <a href="https://www.agilecrm.com/landing-page" target="_blank" class="fb-read">Read more</a>
     </div>
 </div>
 </div>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#landing-pages" method="post" target="_blank" style="display: none;">
<input type="text" required name="email" value="<?php echo $email; ?>" />
<input required type="password" name="password" value="<?php echo $password; ?>" />
<input required type="hidden" name="type" value="agile" />
<input id="agile-top-button-send" class="saveBtn" required type="submit" name="login" value="Login" />
</form>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#pagebuilder" method="post" target="_blank" style="display: none;">
<input type="text" required name="email" value="<?php echo $email; ?>" />
<input required type="password" name="password" value="<?php echo $password; ?>" />
<input required type="hidden" name="type" value="agile" />
<input id="agile-top-button-template2" class="saveBtn" required type="submit" name="login" value="Login" />
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
    if(name== 'LandingPages'){
      window.location.reload();
    }
}});
 function getAgileChannelName(){  
     return  "{{ restapi }}";
    }
</script>