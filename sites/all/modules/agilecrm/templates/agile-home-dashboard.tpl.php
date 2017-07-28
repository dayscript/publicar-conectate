<div id="features">

<form action="/drupal_7/admin/agilecrm/home" method="post">
<?php print $output; ?> 
<input type="hidden" name="featuresform" id="featuresform" value="featuresform"/>
<a href="web-rules" style="text-decoration: none;color: #444;">
<div class="box">
  <div class="right stripline">
   <div class="header"><img src="../../sites/all/modules/agilecrm/images/webrules.svg" width="60px" height="60px" title="Web Rules" alt="webstatus" /></div>
   <h2 class="heading">  Web Rules</h2>
   <h5>Web Rules automate actions in response to user activity on your website.</h5>
   <a href="web-rules" class="more">More</a>
  </div>
</div></a>
<a href="form-builder" style="text-decoration: none;color: #444;">
<div class="box">
  <div class="right stripline">
    <div class="header"><img src="../../sites/all/modules/agilecrm/images/form.svg" width="60px" height="60px" title="Web Rules" alt="webstatus" /></div>
    <div class="left">
    </div>
    <h2 class="heading">Form Builder</h2>
   <h5>Agile helps you create your custom Web Rules at ease and place it on your website.</h5>
   <a href="formbuilder" class="more">More</a>
   </div> 
 </div></a>
<a href="landing-pages" style="text-decoration: none;color: #444;"> 
   <div class="box">
   <div class="right stripline">
   <div class="header"><img src="../../sites/all/modules/agilecrm/images/landing.svg" width="60px" height="60px" title="Web Rules" alt="webstatus" /></div>
   <div class="left">
   </div>
   <h2 class="heading">Landing Pages</h2>
   <h5>The Landing Page Builder helps create high converting landing pages.</h5>
   <a href="landing-pages" class="more">More</a>
 </div>
</div> </a>
<a href="emailcampaigns" style="text-decoration: none;color: #444;">
  <div class="box">
   <div class="right stripline">
    <div class="header"><img src="../../sites/all/modules/agilecrm/images/mail.svg" width="60px" height="60px" title="Web Rules" alt="webstatus" /></div>
    <div class="left">
    </div>
    <h2 class="heading">Email Campaigns</h2>
    <h5>Send newsletters and track performance with Agile CRMs email marketing tools.</h5>
    <a href="emailcampaigns" class="more">More</a>
   </div>
</div> </a>
<a href="web-stats" style="text-decoration: none;color: #444;">
<div class="box">
  <div class="right stripline">
     <div class="header"><img src="../../sites/all/modules/agilecrm/images/webstats.svg" width="60px" height="60px" title="Web Rules" alt="webstatus" /></div>
     <h2 class="heading">Web Stats</h2>
    <h5>Agile gives you deep insight into customer behavior and website performance.</h5>
    <a href="web-stats" class="more">More</a>
  </div> 
</div></a> 
<a href="refer-friend" style="text-decoration: none;color: #444;">
  <div class="box">
    <div class="right stripline">
    <div class="header"><img src="../../sites/all/modules/agilecrm/images/refer a friend.svg" width="60px" height="60px" title="Web Rules" alt="webstatus" /></div>
    <div class="left">
    </div>
    <h2 class="heading">Refer a Friend</h2>
    <h5 style="">Our in-app referral program for all of Agile CRMs users is currently effective.</h5>
    <a style="" class="more"  href="refer-friend">Refer</a>
   </div>
</div></a>
</form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery("#features #edit-webrules").click(function(){
        jQuery("#features form").submit();
    });
});
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery("#features #edit-webstats").click(function(){
        jQuery("#features form").submit();
    });
});
</script>
<style>
.form-item.form-type-checkbox.form-item-webrules {
    display: inline;
}
.form-item.form-type-checkbox.form-item-webstatus {
    display: inline;
}
input#edit-webrules {
    position: absolute;
    left: 173px;
    z-index: 9;
    top: 198px;
}
input#edit-webstats {
    position: absolute;
    left: 540px;
    top: 521px;
    z-index: 9;
}
</style>
</div>