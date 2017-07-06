CKEDITOR.plugins.add( 'my_plugin',  
{
    init: function( editor )
    {

        editor.addCommand( 'my_command', new CKEDITOR.dialogCommand( 'simpleLinkDialog' ) );
        editor.addCommand( 'my_formbuilder', new CKEDITOR.dialogCommand( 'formsimpleLinkDialog' ) );
 
        editor.ui.addButton( 'my_plugin_button', {
        label: 'Agile Landing Page', //this is the tooltip text for the button
        command: 'my_command',
        icon: this.path + 'icons/browser.png'
       });
       editor.ui.addButton( 'form_builder_button', {
        label: 'Agile Form Builder', //this is the tooltip text for the button
        command: 'my_formbuilder',
        icon: this.path + 'icons/googleforms.png'
       });
 
CKEDITOR.dialog.add( 'simpleLinkDialog', function( editor )
        {
            var output = [];
            for(var i = 0; i < landingdata.length; i++) {
            var obj = landingdata[i];
              output[i] = [obj.name,obj.id];
              var defaultvalue = obj.id;
            }
              
            return {
                title : 'Landing Page',
                minWidth : 200,
                minHeight : 150,
                contents :
                [
                    {
                        id : 'general',
                        label : 'Settings',
                        elements :
                        [
                            {
                                type : 'select',
                                id : 'style',
                                label : 'Landing Page',
                                'default':defaultvalue,
                                items :output,
                                commit : function( data )
                                {
                                    data.style = this.getValue();
                                }
                            },
                        ]
                    }
                ],
                onOk : function()
                {
                    var dialog = this,
                    data = {},
                    link = editor.document.createElement('p');
                    this.commitContent( data );
                    data.style =  "{agilelandingpage_id}"+ data.style +"{/agilelandingpage_id}";
                    link.setHtml( data.style );
                    editor.insertElement( link );
                }
            };
        });
CKEDITOR.dialog.add( 'formsimpleLinkDialog', function( editor )
        {
            var replaceString = [];
            for(var i = 0; i < formdata.length; i++) {
            var obj = formdata[i];
              replaceString[i] = [obj.formName,obj.id];
              var defaultvalue = obj.id;
             }
            return {
                title : 'Form Builder',
                minWidth : 200,
                minHeight : 150,
                contents :
                [
                    {
                        id : 'general',
                        label : 'Settings',
                        elements :
                        [
                            {
                                type : 'select',
                                id : 'style1',
                                label : 'Form Builder',
                                items : replaceString,
                                'default':defaultvalue,
                                commit : function( data )
                                {
                                    data.style = this.getValue();
                                }
                            },
                        ]
                    }
                ],
                onOk : function()
                {
                    var dialog = this,
                    data = {},
                    iframe = editor.document.createElement( 'iframe' ),
                    div = editor.document.createElement('div');
                    this.commitContent( data );
                    var idVideo = data.style;
                    data.style = "http://"+ domain +".agilecrm.com/forms/" + idVideo;
                    iframe.setAttribute( 'src', data.style);
                    iframe.setAttribute( 'width', '600' );
                    iframe.setAttribute( 'height', '600' );
                    iframe.setAttribute( 'frameborder', '0');


                    iframe.appendTo(div); //problem is solved here!
                    editor.insertElement(div);
                }
            };
        });
    }
});