    (function() 
	{  
        tinymce.create('tinymce.plugins.quote', 
		{  
            init : function(ed, url) 
			{  
                ed.addButton('quote', 
				{  
                    title : 'Booking+ ShortCode',  
                    image : url+'/icon.png',  
                    onclick : function() 
					{  
                         ed.selection.setContent('[booking color=#aec71e size=30px padding=5px]BOOK NOW[/booking]');  
      
                    }  
                });  
            },  
            createControl : function(n, cm) 
			{  
                return null;  
            },  
        });  
        tinymce.PluginManager.add('quote', tinymce.plugins.quote);  
    })();  