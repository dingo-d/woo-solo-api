��    B      ,  Y   <      �  4   �     �  @   �     2  /   M  ?   }     �  
   �  #   �  $   �          ;     W     f     �  $   �  -   �  ,   �  A   !  F   c  8   �     �     �     �  #   	  $   2	     W	  
   f	     q	  
   �	  5   �	     �	  D   �	  ;   &
     b
  '   o
     �
     �
     �
     �
     �
  /   �
      !     B  7   a  4   �  5   �       I     6   g  
   �  8   �  j   �     M     b     z     �  ;   �     �     �          #  <   ,     i     o  �  v  -   2     `  H   l     �  7   �  B     
   J  
   U     `  #   z     �     �     �     �     �  .   �  K   ,  J   x  M   �  L     4   ^  	   �     �     �     �  #   �               (     H  *   Q     |  /   �  1   �     �  (         )     A     Q  $   `     �  2   �     �     �  /   �  B   &  :   i     �  R   �  8     	   >  A   H  l   �     �          +     B  >   ]     �     �     �     �  [   �     5     <     )   %   A      &          ;   <   '   0   1      >   3   5      B          ?                               ,   (           +       	                  #            *            4         2   @   $      .       /       !       "           8   9      
      :      6   -              7          =                                API request error happened. %1$s. (Error code %2$s). API request error messages Array of gateways that the email with the invoice should be sent Available payment gateways Central bank rates API returned an empty result Change the 'from' name that shows when WordPress sends the mail Company name Created at Currency of the bill or the invoice Currency option needs to be a string Customer billing IBAN number Customer billing PIN number Customer email Customer shipping IBAN number Customer shipping PIN number Due date option needs to be a string Enable IBAN field in the WooCommerce checkout Enable PIN field in the WooCommerce checkout Error in sending customer email. Attachment URL cannot be parsed. Error in sending customer email. Email message must be of string type. Flag that notifies if the PDF bill was sent to customer. IBAN number Is sent to API Is sent to user Language of the bill or the invoice Language option needs to be a string Options saved. Order Item PDF invoice/order is missing PIN number Response details from the API is not of an array type SOLO API Settings Send the email to the client with the PDF of the bill or the invoice Service type. A unique ID of the service (must be a number) Shipping fee Should the invoice be fiscalized or not Show taxes option Solo API Options Solo API token Solo API token must be a string Solo ID Specify the due date on the bill or the invoice The message of the invoice email The title of the invoice email The type of invoice (R, R1, R2, No label or In advance) This plugin requires PHP 7.3 or greater to function. This plugin requires WooCommerce plugin to be active. Type of payment document Type of payment on api gateway (transactional account, cash, cards, etc.) Unit measure of the shop (e.g. piece, hour, m³, etc.) Updated at When to send the PDF - on checkout or order confirmation Woo Orders table seems to be missing. Please reactivate the plugin (deactivate and activate) to create it. Woo Solo Api Options Woo Solo Api Order Data Woo Solo Api Order Items WooCommerce order item ID WordPress internal error happened. %1$s. (Error code %2$s). Your %1$s from %2$s Your %s is in the attachment. invoice invoice- manifest.json is missing. Bundle the plugin before using it. offer offer- Project-Id-Version: Woo Solo Api
Report-Msgid-Bugs-To: https://wordpress.org/support/plugin/woo-solo-api
PO-Revision-Date: 2022-01-09 19:41+0100
Last-Translator: 
Language-Team: Denis Žoljom
Language: hr
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<12 || n%100>14) ? 1 : 2);
X-Generator: Poedit 3.0.1
X-Poedit-Basepath: ..
X-Poedit-SourceCharset: UTF-8
X-Poedit-KeywordsList: e__;esc_html__;esc_html_e;esc_attr__;esc_attr_e;esc_html_x;__;_n_noop
X-Poedit-SearchPath-0: assets
X-Poedit-SearchPath-1: src
X-Poedit-SearchPathExcluded-0: bin
X-Poedit-SearchPathExcluded-1: languages
X-Poedit-SearchPathExcluded-2: node_modules
X-Poedit-SearchPathExcluded-3: tests
X-Poedit-SearchPathExcluded-4: vendor
X-Poedit-SearchPathExcluded-5: views
X-Poedit-SearchPathExcluded-6: webpack
X-Poedit-SearchPathExcluded-7: assets/public
 API poziv ima grešku. %1$s. (Error kod %2$s) API greške Lista platnih procesora za koje bi e-mail prema kupcu trebao biti poslan Dostupni platni procesori API za stope središnje banke vratio je prazan rezultat Promijenite 'od' ime koje se pokazuje kada WordPress šalje e-mail Ime tvrtke Napravljen Valuta ponude ili računa Opcija valute mora biti niz znakova IBAN broj za naplatu OIB broj za naplatu E-mail kupca IBAN broj za dostavu OIB broj za dostavu Opcija datuma dospijeća mora biti niz znakova Omogućite IBAN polje na formi za plaćanje i dostavu na naplatnoj stranici Omogućite OIB polje na formi za plaćanje i dostavu na naplatnoj stranici Pogreška u slanju e-pošte klijentu. URL privitka nije moguće raščlaniti. Pogreška u slanju e-pošte klijentu. Poruka e-pošte mora biti niz znakova. Oznaka koja označava je li PDF račun poslan kupcu. IBAN broj Je li poslan na Solo API Je li poslan kupcu Jezik ponude ili računa Opcija jezika mora biti niz znakova Opcije spremljene. Broj narudžbe Nedostaje PDF faktura/narudžba OIB broj Detalji odgovora iz API-ja nisu tipa polja Solo API opcije Pošaljite email kupcu s PDF računom u dodatku Tip usluge. Jedinstven ID usluge (mora biti broj) Naknada za slanje Treba li račun biti fiskaliziran ili ne Opcija za prikaz poreza Solo API opcije Solo Api token Solo API token mora biti niz znakova Solo ID Specificirajte datum dospijeća ponude ili računa Poruka e-maila sa računom Naslov e-maila sa računom Tip računa (R, R1, R2, Bez oznake ili avansni) Ovaj dodatak zhtjeva PHP verziju 7.3 ili veću da bi funkcionirao. Ovaj dodatak zahtjeva da WooCommerce dodatak bude aktivan. Tip plaćanja Tip plaćanja na platnom procesoru (transakcijski račun, gotovina, kartice, itd.) Jedinična mjera za trgovinu (npr. komad, sat, m³ itd.) Ažuriran Kada poslati PDF - na stranici plaćanja ili na potvrdu narudžbe Tablica s narudžbama nedostaje u bazi. Molimo vas da deaktivirate i aktivirate plugin da biste ju kreirali. Woo Solo API opcije Woo Solo API podaci o narudžbi Woo Solo Api narudžbe WooCommerce broj narudžbe Dogodila se interna WordPress greška. %1$s. (Error kod %2$s). Vaš %1$s od %2$s Vaš %s se nalazi u privitku. racun racun- manifest.json nedostaje. Dodatak more proći kroz webpack bundle process prije korištenja. ponuda ponuda- 