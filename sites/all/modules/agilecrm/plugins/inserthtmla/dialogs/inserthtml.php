<?php 
 $connection = mysql_connect("localhost", 
                            "root", 
                            "agile123");
mysql_select_db("drupal7", $connection); 
$result = mysql_query("SELECT * FROM pages"); ?>
 
CKEDITOR.dialog.add("inserthtml",function(e){   
   return{
      title:'Link to Page',
      resizable : CKEDITOR.DIALOG_RESIZE_BOTH,
      minWidth:380,
      minHeight:220,
      onShow:function(){ 
      },
      onLoad:function(){ 
            dialog = this; 
            this.setupContent();
      },
      onOk:function(){
         var sInsert=this.getValueOf('info','insertcode_area');   
         if ( sInsert.length > 0 ) 
         e.insertHtml(sInsert); 
      },
      contents:[
         {   id:"info",
            name:'info',
            label:'Link to Page',
            elements:[{
             type:'vbox',
             padding:0,
             children:[
              {type:'html',
              html:'<span>Select Page to link to</span>'
              },
              { type:'select',
                id:'selectPage',
               label:'select Page',
               items:[
                    <?php $i = 1;
               while($row = mysql_fetch_assoc($result))
                  {
                     if($i == 1) {
                        echo "['$row[title]','$row[id]']";
                        $i = null;
                     }
                     else
                        echo ",['$row[title]','$row[id]']";
                  }
               ?>['one','1']]
              }]
            }]
         }
      ]
   };
});