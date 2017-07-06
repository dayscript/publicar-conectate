CKEDITOR.plugins.add('my_plugin',   
  {    
    requires: ['dialog'],
   lang : ['en'], 
    init:function(a) { 
   var b="agilecrm";
   var c=a.addCommand(b,new CKEDITOR.dialogCommand(b));
      c.modes={wysiwyg:1,source:0};
      c.canUndo=false;
   a.ui.addButton("my_plugin_button",{
               label: 'Agile Landing Page',
               command:b,
               icon:this.path+'icons/browser.png'
   });
   CKEDITOR.dialog.add(b,this.path+"dialogs/inserthtml.php")}
});