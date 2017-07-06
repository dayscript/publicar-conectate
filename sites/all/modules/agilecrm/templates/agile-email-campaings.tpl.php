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
<form action="" method="post"><input type="hidden" name="featuresform" value="featuresform">
<div class="mainLeftbox" style="border-right: 0px">
 <div class="crm-form-list" style="margin-top:30px;">           <div class="formbilder"><div class="add-forms"><a class="more" onclick="document.getElementById('agile-top-button-send').click();return false;" target="_blank" href="#">Manage Campaigns</a>
          <a onclick="window.location.reload();" title="Refresh" class="reload more">↻</a></div></div>
                        <div class="formbilder">
                 <table class="wp-list-table widefat plugins" id="form_builder_list">
                   <thead>
                     <tr><th scope="col" id="name" class="manage-column column-name column-primary">S.No</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                       <th scope="col" id="description" class="manage-column column-description"></th>  
                     </tr>
                   </thead>
                    <tbody id="the-list">
                   <?php $i= 1;
                   if($form_output){
                   foreach($form_output as $v){  
                   echo "<tr><th>".$i.".</th><th>".ucfirst($v->name)."</th>"; ?>
                   <th style="text-align: center;"><?php $disable = $v->is_disabled; 
                   if($disable){ ?><span style="background-color: #d9534f;font-weight: normal;text-shadow: 0 1px 0 rgba(0,0,0,0.2);display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: bold;line-height: 1;color: #fff;text-align: center;white-space: nowrap;vertical-align: baseline;border-radius: .25em;">Disabled</span> <?php } ?></th>
                  </tr>
                  <?php $i++; } } else { ?>
              
                        <tr><th id='count' colspan='3'>Sorry, you dont have any Campaigns yet.</th></tr>
                       <?php 
                         } ?>
                  </tbody>
                 </table>
               </div>
              </div>
</div>
<div class="mainrightbox" style="border-left: 1px solid #d2d2d2;">
  <div class="box-right">  
  <div id="my-content-id_webrules">
<h3 class="font-thin h3">Campaigns</h3>
<p>Email Campaigns-Track the effectiveness of your email campaigns with real-time notifications for email opens. Email tracking software takes on a better way to give the analytics on campaign emails. </p>
 <img src="../../sites/all/modules/agilecrm/images/email.png" class="contentimage" title="Email Campaigns" width="95%">  
 <p>Enjoy effective templates, personalization, scoring and tagging, advanced reporting, automated responders, real-time alerts and Email A/B testing with Agile's Email Marketing CRM.</p>
</div>
<div id="my-content-id_webrules" style="margin-top:15px;">
<h3 class="font-thin h3">News Letters</h3>
<p>Newsletter-Track the effectiveness of your newsletter campaigns with real-time notifications for email opens. Email tracking software takes on a better way to give the analytics on campaign emails.</p>
<img src="https://cdnsite.agilecrm.com/img/newsletters/campaign-newsletter.png" class="contentimage" alt="News letters" width="95%">
<p> Enjoy effective templates, personalization, scoring and tagging, advanced reporting, automated responders, real-time alerts and Email A/B testing.</p>
</div>      
      <h3 class="m-t-none h4 m-b-sm">Email Campaigns</h3>
      <p>
      Run bulk email campaigns, send newsletters and track performance with Agile CRM's email marketing tools. Enjoy effective templates, personalization, scoring and tagging, advanced reporting, automated responders, real-time alerts and Email A/B testing with Agile's Email Marketing CRM.</p>
       <p>
        <iframe width="100%" height="180px" src="https://www.youtube.com/embed/pXwKUnQa5Ec" frameborder="0" allowfullscreen="" class="wp-campaigns-video"></iframe>
     </p>
     <a href="https://www.agilecrm.com/email-marketing" target="_blank" class="fb-read">Read more</a>
     </div>

 </div>
</form></div>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=schedulenow" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-schedulenow" class="saveBtn" required="" type="submit" name="logind" value="Logind">
   </form>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=theme3" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-theme3" class="saveBtn" required="" type="submit" name="logind" value="Logind">
   </form>
   <form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=theme4" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-theme4" class="saveBtn" required="" type="submit" name="logind" value="Logind">
   </form>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=getintouchwithus" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-getintouchwithus" class="saveBtn" required="" type="submit" name="logind" value="Logind">
   </form>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=subscribenow" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-subscribenow" class="saveBtn" required="" type="submit" name="logind" value="Logind">
   </form>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#formbuilder?template=theme2" method="post" target="_blank" style="display: none;">
                <input type="text" required="" name="email" value="<?php echo $email; ?>">
                <input required="" type="text" name="password" value="<?php echo $password; ?>">
                <input required="" type="text" name="type" value="agile">
                <input id="agile-top-button-theme2" class="saveBtn" required="" type="submit" name="logind" value="Logind">
</form>
<form action="https://<?php echo $domain; ?>.agilecrm.com/login#workflows" method="post" target="_blank" style="display: none;">
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
    if(name== 'Campaigns'){
      window.location.reload();
    }
}});
 function getAgileChannelName(){  
     return  "<?php echo $restapkey; ?>";
    }
</script>