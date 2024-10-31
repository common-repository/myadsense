			function MyAdsense_set_url(LANG)
			{

				corner = document.getElementById('corner').value;
				adfakeurl = 'https://www.google.com/pagead/ads?client=ca-google-asfe&format=160x70_as' + get_colors() + '&hl=' + LANG + '&ui=rc:' + corner;
				linkfakeurl = MyAdsense_PATH + "/php/fakelink.php?x=x" + get_colors();

				document.getElementById('adiframe').src = adfakeurl;
				document.getElementById('linkiframe').src = linkfakeurl;


				function get_colors()
				{
					var field;
					var url = '';
					var fields = new Array();
					fields[0] = 'color_border';
					fields[1] = 'color_bg';
					fields[2] = 'color_link';
					fields[3] = 'color_text';
					fields[4] = 'color_url';

					for (i=0;i<fields.length;i++)
					{
						url = url + '&' + fields[i] + '=' + get_color(fields[i]);
					}
					return url;
				}
				function get_color(field)
				{
					if (document.getElementById(field).value != '')
					{
						couleur = document.getElementById( field ).value;
						truecolor = new RGBColor(couleur);
						if (truecolor.ok) return truecolor.toHexb();
					}
					field = field + '_def';
					return document.getElementById( field ).value;
				}
			}

			function MyAdsense_product_options(value)
			{
				switch (value)
				{
					case 'link':	
						show = 'link';
						hide = 'ad';
					break;
					case 'ad':	
						show = 'ad';
						hide = 'link';
					break;
				}
				document.getElementById('div-' + hide + 'lib').style.display='none';
				document.getElementById('div-' + hide + 'format').style.display='none';
				document.getElementById('div-' + hide + 'type').style.display='none';
				document.getElementById('ad' + hide ).style.display='none';

				document.getElementById('div-' + show + 'lib').style.display='';
				document.getElementById('div-' + show + 'format').style.display='';
				document.getElementById('div-' + show + 'type').style.display='';
				document.getElementById('ad'   + show ).style.display='';
				
				if (show == 'ad')
				{
					document.getElementById('adcorner').style.display='';
				}
				else
				{
					document.getElementById('adcorner').style.display='none'
				}
			}
			function MyAdsense_product_ad()
			{
				MyAdsense_product_options('ad');
			}
			function MyAdsense_product_link()
			{
				MyAdsense_product_options('link');
			}