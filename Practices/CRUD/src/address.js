
function updateCities(selectedState) {
    var citySelect = document.getElementById("citySelect");
    citySelect.innerHTML = "";

    var philippines_cities = {
        "Metro Manila": ["Caloocan", "Las Piñas", "Makati", "Malabon", "Mandaluyong", "Manila", "Marikina", "Muntinlupa", "Navotas", "Parañaque", "Pasay", "Pasig", "Pateros", "Quezon City", "San Juan", "Taguig", "Valenzuela"],
            "Cebu": ["Cebu City", "Lapu-Lapu City", "Mandaue City", "Toledo City", "Bogo City", "Talisay City"],
    
            "Ilocos Norte": ["Adams", "Bacarra", "Badoc", "Bangui", "Banna", "Batac", "Burgos", "Carasi", "Currimao", "Dingras", "Dumalneg", "Marcos", "Nueva Era", "Pagudpud", "Paoay", "Pasuquin", "Piddig", "Pinili", "San Nicolas", "Sarrat", "Solsona", "Vintar"],
            "Ilocos Sur": ["Alilem", "Banayoyo", "Bantay", "Burgos", "Cabugao", "Candon", "Caoayan", "Cervantes", "Galimuyod", "Gregorio del Pilar", "Lidlidda", "Magsingal", "Nagbukel", "Narvacan", "Quirino", "Salcedo", "San Emilio", "San Esteban", "San Ildefonso", "San Juan", "San Vicente", "Santa", "Santa Catalina", "Santa Cruz", "Santa Lucia", "Santa Maria", "Santiago", "Santo Domingo", "Sigay", "Sinait", "Sugpon", "Suyo", "Tagudin", "Vigan"],
            "La Union": ["Agoo", "Aringay", "Bacnotan", "Bagulin", "Balaoan", "Bangar", "Bauang", "Burgos", "Caba", "Luna", "Naguilian", "Pugo", "Rosario", "San Fernando", "San Gabriel", "San Juan", "Santo Tomas", "Santol", "Sudipen", "Tubao"],
            "Pangasinan": ["Agno", "Aguilar", "Alcala", "Anda", "Asingan", "Balungao", "Bani", "Basista", "Bautista", "Bayambang", "Binalonan", "Binmaley", "Bolinao", "Bugallon", "Burgos", "Calasiao", "Dagupan", "Dasol", "Infanta", "Labrador", "Laoac", "Lingayen", "Mabini", "Malasiqui", "Manaoag", "Mangaldan", "Mangatarem", "Mapandan", "Natividad", "Pozorrubio", "Rosales", "San Carlos", "San Fabian", "San Jacinto", "San Manuel", "San Nicolas", "San Quintin", "Santa Barbara", "Santa Maria", "Santo Tomas", "Sison", "Sual", "Tayug", "Umingan", "Urbiztondo", "Villasis"],
   
            "Batanes": ["Basco", "Itbayat", "Ivana", "Mahatao", "Sabtang", "Uyugan"],
             "Cagayan": ["Tuguegarao City", "Abulug", "Alcala", "Allacapan", "Amulung", "Aparri", "Baggao", "Ballesteros", "Buguey", "Calayan", "Camalaniugan", "Claveria", "Enrile", "Gattaran", "Gonzaga", "Iguig", "Lal-lo", "Lasam", "Pamplona", "Peñablanca", "Piat", "Rizal", "Sanchez-Mira", "Santa Ana", "Santa Praxedes", "Santa Teresita", "Santo Niño", "Solana", "Tuao"],
            "Isabela": ["Ilagan City", "Cauayan City", "Alicia", "Angadanan", "Aurora", "Benito Soliven", "Burgos", "Cabagan", "Cabatuan", "Cordon", "Delfin Albano", "Dinapigue", "Divilacan", "Echague", "Gamu", "Jones", "Luna", "Maconacon", "Mallig", "Naguilian", "Palanan", "Quezon", "Quirino", "Ramon", "Reina Mercedes", "Roxas", "San Agustin", "San Guillermo", "San Isidro", "San Manuel", "San Mariano", "San Mateo", "San Pablo", "Santa Maria", "Santiago City", "Santo Tomas", "Tumauini"],
            "Nueva Vizcaya": ["Bayombong", "Alfonso Castaneda", "Ambaguio", "Aritao", "Bagabag", "Bambang", "Diadi", "Dupax del Norte", "Dupax del Sur", "Kasibu", "Kayapa", "Quezon", "Santa Fe", "Solano", "Villaverde"],
            "Quirino": ["Aglipay", "Cabarroguis", "Diffun", "Maddela", "Nagtipunan", "Saguday"],
     
            "Aurora": ["Baler", "Casiguran", "Dilasag", "Dinalungan", "Dingalan", "Dipaculao", "Maria Aurora", "San Luis"],
            "Bataan": ["Balanga City", "Abucay", "Bagac", "Dinalupihan", "Hermosa", "Limay", "Mariveles", "Morong", "Orani", "Orion", "Pilar", "Samal"],
            "Bulacan": ["Malolos City", "Meycauayan City", "San Jose del Monte City", "Angat", "Balagtas", "Baliuag", "Bocaue", "Bulacan", "Bustos", "Calumpit", "Doña Remedios Trinidad", "Guiguinto", "Hagonoy", "Marilao", "Norzagaray", "Obando", "Pandi", "Paombong", "Plaridel", "Pulilan", "San Ildefonso", "San Miguel", "San Rafael", "Santa Maria"],
            "Nueva Ecija": ["Palayan City", "Cabanatuan City", "Gapan City", "San Jose City", "Aliaga", "Bongabon", "Cabiao", "Carranglan", "Cuyapo", "Gabaldon", "General Mamerto Natividad", "General Tinio", "Guimba", "Jaen", "Laur", "Licab", "Llanera", "Lupao","Munoz", "Nampicuan", "Pantabangan", "Peñaranda", "Quezon", "Rizal", "San Antonio", "San Isidro", "San Leonardo", "Santa Rosa", "Santo Domingo", "Talavera", "Talugtug", "Zaragoza"],
            "Pampanga": ["Angeles City", "City of San Fernando", "Apalit", "Arayat", "Bacolor", "Candaba", "Floridablanca", "Guagua", "Lubao", "Mabalacat", "Macabebe", "Magalang", "Masantol", "Mexico", "Minalin", "Porac", "San Luis", "San Simon", "Santa Ana", "Santa Rita", "Santo Tomas", "Sasmuan"],
            "Tarlac": ["Tarlac City", "Capas", "Concepcion", "Gerona", "La Paz", "Mayantoc", "Moncada", "Paniqui", "Pura", "Ramos", "San Clemente", "San Jose", "San Manuel", "Santa Ignacia", "Victoria"],
            "Zambales": ["Olongapo City", "Iba", "Botolan", "Cabangan", "Candelaria", "Castillejos", "San Antonio", "San Felipe", "San Marcelino", "San Narciso", "Santa Cruz", "Subic"],

            "Batangas": ["Batangas City", "Lipa City", "Tanauan City", "Balayan", "Balete", "Bauan", "Calaca", "Calatagan", "Cuenca", "Ibaan", "Lemery", "Lian", "Lobo", "Mabini", "Malvar", "Mataasnakahoy", "Nasugbu", "Padre Garcia", "Rosario", "San Jose", "San Juan", "San Luis", "San Nicolas", "San Pascual", "Santa Teresita", "Santo Tomas", "Taal", "Talisay", "Taysan", "Tingloy", "Tuy"],
            "Cavite": ["Cavite City", "Tagaytay City", "Trece Martires City", "Alfonso", "Amadeo", "Bacoor", "Carmona", "General Emilio Aguinaldo", "General Mariano Alvarez", "General Trias", "Imus", "Indang", "Kawit", "Magallanes", "Maragondon", "Mendez", "Naic", "Noveleta", "Rosario", "Silang", "Tanza", "Ternate"],
            "Laguna": ["Calamba City", "San Pablo City", "Santa Rosa City", "Biñan City", "Cabuyao City", "Los Baños", "San Pedro City", "Alaminos", "Bay", "Cabangan", "Calauan", "Cavinti", "Famy", "Kalayaan", "Liliw", "Luisiana", "Lumban", "Mabitac", "Magdalena", "Majayjay", "Nagcarlan", "Paete", "Pagsanjan", "Pakil", "Pangil", "Pila", "Rizal", "Santa Cruz", "Santa Maria", "Siniloan", "Victoria"],
            "Quezon": ["Lucena City", "Tayabas City", "Candelaria", "Sariaya", "Alabat", "Atimonan", "Buenavista", "Burdeos", "Calauag", "Catanauan", "Dolores", "General Luna", "General Nakar", "Guinayangan", "Gumaca", "Infanta", "Jomalig", "Lopez", "Lucban", "Macalelon", "Mauban", "Mulanay", "Padre Burgos", "Pagbilao", "Panukulan", "Patnanungan", "Perez", "Pitogo", "Plaridel", "Polillo", "Quezon", "Real", "Sampaloc", "San Andres", "San Antonio", "San Francisco", "San Narciso", "Sariaya", "Tagkawayan", "Tiaong", "Unisan"],
            "Rizal": ["Antipolo City", "Angono", "Baras", "Binangonan", "Cainta", "Cardona", "Jalajala", "Morong", "Pililla", "Rodriguez", "San Mateo", "Tanay", "Taytay", "Teresa"],
     
            "Marinduque": ["Boac", "Buenavista", "Gasan", "Mogpog", "Santa Cruz", "Torrijos"],
            "Occidental Mindoro": ["Mamburao", "Abra de Ilog", "Calintaan", "Looc", "Lubang", "Magsaysay", "Paluan", "Rizal", "Sablayan", "San Jose"],
            "Oriental Mindoro": ["Calapan City", "Baco", "Bansud", "Bongabong", "Bulalacao", "Gloria", "Mansalay", "Naujan", "Pinamalayan", "Pola", "Puerto Galera", "Roxas", "San Teodoro", "Socorro", "Victoria"],
            "Palawan": ["Puerto Princesa City", "Aborlan", "Agutaya", "Araceli", "Balabac", "Bataraza", "Brooke's Point", "Busuanga", "Cagayancillo", "Coron", "Culion", "Cuyo", "Dumaran", "El Nido", "Kalayaan", "Linapacan", "Magsaysay", "Narra", "Quezon", "Rizal", "Roxas", "San Vicente", "Sofronio Española", "Taytay"],
            "Romblon": ["Romblon", "Calatrava", "San Agustin", "Corcuera", "Banton", "Concepcion", "Odiongan", "Magdiwang", "San Fernando", "Santa Fe"],
            
            "Abra": ["Bangued", "Boliney", "Bucay", "Bucloc", "Daguioman", "Danglas", "Dolores", "La Paz", "Lacub", "Lagangilang", "Lagayan", "Langiden", "Licuan-Baay", "Luba", "Malibcong", "Manabo", "Penarrubia", "Pidigan", "Pilar", "Sallapadan", "San Isidro", "San Juan", "San Quintin", "Tayum", "Tineg", "Tubo", "Villaviciosa"],
            "Apayao": ["Calanasan", "Conner", "Flora", "Kabugao", "Luna", "Pudtol", "Santa Marcela"],
            "Benguet": ["Atok", "Baguio", "Bakun", "Bokod", "Buguias", "Itogon", "Kabayan", "Kapangan", "Kibungan", "La Trinidad", "Mankayan", "Sablan", "Tuba", "Tublay"],
            "Ifugao": ["Lagawe", "Aguinaldo", "Alfonso Lista", "Asipulo", "Banaue", "Hingyon", "Hungduan", "Kiangan", "Mayoyao", "Tinoc"],
            "Kalinga": ["Tabuk City", "Balbalan", "Lubuagan", "Pasil", "Pinukpuk", "Rizal", "Tanudan", "Tinglayan"],
            "Mountain Province": ["Bontoc", "Barlig", "Besao", "Bontoc", "Natonin", "Paracelis", "Sabangan", "Sadanga", "Sagada", "Tadian"],

            "Albay": ["Legazpi City", "Ligao City", "Tabaco City", "Bacacay", "Camalig", "Daraga", "Guinobatan", "Jovellar", "Libon", "Malilipot", "Malinao", "Manito", "Oas", "Pio Duran", "Polangui", "Rapu-Rapu", "Santo Domingo", "Tiwi"],
"Camarines Norte": ["Daet", "Basud", "Capalonga", "Jose Panganiban", "Labo", "Mercedes", "Paracale", "San Lorenzo Ruiz", "San Vicente", "Santa Elena", "Talisay", "Vinzons"],
"Camarines Sur": ["Naga City", "Iriga City", "Baao", "Balatan", "Bato", "Bombon", "Buhi", "Bula", "Cabusao", "Calabanga", "Camaligan", "Canaman", "Caramoan", "Del Gallego", "Gainza", "Garchitorena", "Goa", "Lagonoy", "Libmanan", "Lupi", "Magarao", "Milaor", "Minalabac", "Nabua", "Ocampo", "Pamplona", "Pasacao", "Pili", "Presentacion", "Ragay", "Sagnay", "San Fernando", "San Jose", "Sipocot", "Siruma", "Tigaon", "Tinambac"],
"Catanduanes": ["Virac", "Bagamanoc", "Baras", "Bato", "Caramoran", "Gigmoto", "Pandan", "Panganiban", "San Andres", "San Miguel", "Viga"],
"Masbate": ["Masbate City", "Aroroy", "Baleno", "Balud", "Batuan", "Cataingan", "Cawayan", "Claveria", "Dimasalang", "Esperanza", "Mandaon", "Milagros", "Mobo", "Monreal", "Palanas", "Pio V. Corpuz", "Placer", "San Fernando", "San Jacinto", "San Pascual", "Uson"],

"Aklan": ["Kalibo", "Banga", "Batan", "Buruanga", "Ibajay", "Lezo", "Libacao", "Madalag", "Makato", "Malay", "Malinao", "Nabas", "New Washington", "Numancia", "Tangalan"],
"Antique": ["San Jose de Buenavista", "Anini-y", "Barbaza", "Belison", "Bugasong", "Caluya", "Culasi", "Hamtic", "Laua-an", "Libertad", "Pandan", "Patnongon", "San Remigio", "Sebaste", "Sibalom", "Tibiao", "Tobias Fornier", "Valderrama"],
"Capiz": ["Roxas City", "Cuartero", "Dao", "Dumalag", "Dumarao", "Ivisan", "Jamindan", "Maayon", "Mambusao", "Panay", "Panitan", "Pilar", "Pontevedra", "President Roxas", "Sapi-an", "Sigma", "Tapaz"],
"Guimaras": ["Jordan", "Buenavista", "Nueva Valencia", "San Lorenzo", "Sibunag"],
"Iloilo": ["Iloilo City", "Passi City", "Ajuy", "Alimodian", "Anilao", "Badiangan", "Balasan", "Banate", "Barotac Nuevo", "Barotac Viejo", "Batad", "Bingawan", "Cabatuan", "Calinog", "Carles", "Concepcion", "Dingle", "Dueñas", "Dumangas", "Estancia", "Guimbal", "Igbaras", "Janiuay", "Lambunao", "Leganes", "Lemery", "Leon", "Maasin", "Miagao", "Mina", "New Lucena", "Oton", "Pavia", "Pototan", "San Dionisio", "San Enrique", "San Joaquin", "San Miguel", "San Rafael", "Santa Barbara", "Sara", "Tigbauan", "Tubungan", "Zarraga"],
"Negros Occidental": ["Bacolod City", "Bago City", "Cadiz City", "Escalante City", "Himamaylan City", "Kabankalan City", "La Carlota City", "Sagay City", "San Carlos City", "Silay City", "Sipalay City", "Talisay City", "Victorias City", "Binalbagan", "Calatrava", "Candoni", "Cauayan", "Enrique B. Magalona", "Hinigaran", "Hinoba-an", "Ilog", "Isabela", "La Castellana", "Manapla", "Moises Padilla", "Murcia", "Pontevedra", "Pulupandan", "Salvador Benedicto", "San Enrique", "Toboso", "Valladolid"],
"Bohol": ["Tagbilaran City", "Alburquerque", "Alicia", "Anda", "Antequera", "Baclayon", "Balilihan", "Batuan", "Bien Unido", "Bilar", "Buenavista", "Calape", "Candijay", "Carmen", "Catigbian", "Clarin", "Corella", "Cortes", "Dagohoy", "Danao", "Dauis", "Dimiao", "Duero", "Garcia Hernandez", "Getafe", "Guindulman", "Inabanga", "Jagna", "Lila", "Loay", "Loboc", "Loon", "Mabini", "Maribojoc", "Panglao", "Pilar", "President Carlos P. Garcia", "Sagbayan", "San Isidro", "San Miguel", "Sevilla", "Sierra Bullones", "Sikatuna", "Talibon", "Trinidad", "Tubigon", "Ubay", "Valencia"],
"Cebu": ["Cebu City", "Carcar City", "Danao City", "Lapu-Lapu City", "Mandaue City", "Naga City", "Talisay City", "Bogo City", "Toledo City", "Balamban", "Bantayan", "Barili", "Boljoon", "Borbon", "Carmen", "Catmon", "Compostela", "Consolacion", "Cordova", "Daanbantayan", "Dalaguete", "Dumanjug", "Ginatilan", "Liloan", "Madridejos", "Malabuyoc", "Medellin", "Minglanilla", "Moalboal", "Oslob", "Pilar", "Pinamungahan", "Poro", "Ronda", "Samboan", "San Fernando", "San Francisco", "San Remigio", "Santa Fe", "Santander", "Sibonga", "Sogod", "Tabogon", "Tabuelan", "Tuburan", "Tudela"],
"Negros Oriental": ["Dumaguete City", "Bais City", "Bayawan City", "Guihulngan City", "Tanjay City", "Amlan", "Ayungon", "Bacong", "Basay", "Bindoy", "Dauin", "Jimalalud", "La Libertad", "Mabinay", "Manjuyod", "Pamplona", "San Jose", "Santa Catalina", "Siaton", "Sibulan", "Tayasan", "Valencia", "Vallehermoso", "Zamboanguita"],
"Siquijor": ["Siquijor", "Enrique Villanueva", "Larena", "Lazi", "Maria", "San Juan"],
"Biliran": ["Naval", "Almeria", "Biliran", "Cabucgayan", "Caibiran", "Culaba", "Kawayan", "Maripipi"],
"Eastern Samar": ["Borongan City", "Arteche", "Balangiga", "Balangkayan", "Can-avid", "Dolores", "General MacArthur", "Giporlos", "Guiuan", "Hernani", "Jipapad", "Lawaan", "Llorente", "Maslog", "Maydolong", "Mercedes", "Oras", "Quinapondan", "Salcedo", "San Julian", "San Policarpo", "Sulat", "Taft"],
"Leyte": ["Tacloban City", "Ormoc City", "Baybay City", "Abuyog", "Alangalang", "Albuera", "Babatngon", "Barugo", "Bato", "Burauen", "Calubian", "Capoocan", "Carigara", "Dagami", "Dulag", "Hilongos", "Hindang", "Inopacan", "Isabel", "Jaro", "Javier", "Julita", "Kananga", "La Paz", "Leyte", "MacArthur", "Mahaplag", "Matag-ob", "Matalom", "Mayorga", "Merida", "Palo", "Palompon", "Pastrana", "San Isidro", "San Miguel", "Santa Fe", "Tabango", "Tabontabon", "Tanauan", "Tolosa", "Tunga"],
"Northern Samar": ["Catarman", "Allen", "Biri", "Bobon", "Capul", "Catubig", "Gamay", "Laoang", "Lapinig", "Las Navas", "Lavezares", "Lope de Vega", "Mapanas", "Mondragon", "Palapag", "Pambujan", "Rosario", "San Antonio", "San Isidro", "San Jose", "San Roque", "San Vicente", "Silvino Lobos", "Victoria"],
"Samar (Western Samar)": ["Calbayog City", "Catbalogan City", "Almagro", "Basey", "Calbiga", "Daram", "Gandara", "Hinabangan", "Jiabong", "Marabut", "Matuguinao", "Motiong", "Pagsanghan", "Paranas", "Pinabacdao", "San Jorge", "San Jose de Buan", "San Sebastian", "Santa Margarita", "Santa Rita", "Santo Niño", "Tagapul-an", "Talalora", "Tarangnan", "Villareal", "Zumarraga"],
"Southern Leyte": ["Maasin City", "Anahawan", "Bontoc", "Hinunangan", "Hinundayan", "Libagon", "Liloan", "Limasawa", "Macrohon", "Malitbog", "Padre Burgos", "Pintuyan", "Saint Bernard", "San Francisco", "San Juan", "San Ricardo", "Silago", "Sogod", "Tomas Oppus"],
"Zamboanga del Norte": ["Dapitan City", "Dipolog City", "Bacungan", "Baliguian", "Godod", "Gutalac", "Jose Dalman", "Kalawit", "Katipunan", "La Libertad", "Labason", "Leon B. Postigo", "Liloy", "Manukan", "Mutia", "Piñan", "Polanco", "President Manuel A. Roxas", "Rizal", "Salug", "Sergio Osmeña Sr.", "Siayan", "Sibuco", "Sibutad", "Sindangan", "Siocon", "Sirawai", "Tampilisan"],
"Zamboanga del Sur": ["Pagadian City", "Zamboanga City", "Aurora", "Bayog", "Dimataling", "Dinas", "Dumalinao", "Dumingag", "Guipos", "Josefina", "Kumalarang", "Labangan", "Lakewood", "Lapuyan", "Mahayag", "Margosatubig", "Midsalip", "Molave", "Pitogo", "Ramon Magsaysay", "San Miguel", "San Pablo", "Sominot", "Tabina", "Tambulig", "Tigbao", "Tukuran", "Vincenzo A. Sagun"],
"Zamboanga Sibugay": ["Alicia", "Buug", "Diplahan", "Imelda", "Ipil", "Kabasalan", "Mabuhay", "Malangas", "Naga", "Olutanga", "Payao", "Roseller T. Lim", "Siay", "Talusan", "Titay", "Tungawan"],
"Bukidnon": ["Malaybalay City", "Valencia City", "Baungon", "Cabanglasan", "Damulog", "Dangcagan", "Don Carlos", "Impasugong", "Kadingilan", "Kalilangan", "Kibawe", "Kitaotao", "Lantapan", "Libona", "Malitbog", "Manolo Fortich", "Maramag", "Pangantucan", "Quezon", "San Fernando", "Sumilao", "Talakag"],
"Camiguin": ["Mambajao", "Catarman", "Guinsiliban", "Mahinog", "Sagay"],
"Lanao del Norte": ["Iligan City", "Bacolod", "Baloi", "Baroy", "Kapatagan", "Kauswagan", "Kolambugan", "Lala", "Linamon", "Magsaysay", "Maigo", "Matungao", "Munai", "Nunungan", "Pantao Ragat", "Pantar", "Poona Piagapo", "Salvador", "Sapad", "Sultan Naga Dimaporo", "Tagoloan", "Tangcal", "Tubod"],
"Misamis Occidental": ["Oroquieta City", "Ozamiz City", "Tangub City", "Aloran", "Baliangao", "Bonifacio", "Calamba", "Clarin", "Concepcion", "Don Victoriano Chiongbian", "Jimenez", "Lopez Jaena", "Panaon", "Plaridel", "Sapang Dalaga", "Sinacaban", "Tudela"],
"Misamis Oriental": ["Cagayan de Oro City", "Gingoog City", "El Salvador City", "Alubijid", "Balingasag", "Balingoan", "Binuangan", "Claveria", "Gitagum", "Initao", "Jasaan", "Kinoguitan", "Lagonglong", "Laguindingan", "Libertad", "Lugait", "Magsaysay", "Manticao", "Medina", "Naawan", "Opol", "Salay", "Sugbongcogon", "Tagoloan", "Talisayan", "Villanueva"],
"Compostela Valley (Davao de Oro)": ["Nabunturan", "Compostela", "Laak", "Mabini", "Maco", "Maragusan", "Mawab", "Monkayo", "Montevista", "New Bataan", "Pantukan"],
"Davao del Norte": ["Tagum City", "Island Garden City of Samal", "Braulio E. Dujali", "Carmen", "Kapalong", "New Corella", "San Isidro", "Santo Tomas", "Talaingod"],
"Davao del Sur": ["Digos City", "Davao City", "Bansalan", "Don Marcelino", "Hagonoy", "Kiblawan", "Magsaysay", "Malalag", "Matanao", "Padada", "Santa Cruz", "Sulop"],
"Davao Occidental": ["Malita", "Don Marcelino", "Jose Abad Santos", "Sarangani", "Sta. Maria"],
"Davao Oriental": ["Mati City", "Baganga", "Banaybanay", "Boston", "Caraga", "Cateel", "Governor Generoso", "Lupon", "Manay", "San Isidro", "Tarragona"],
"Cotabato (North Cotabato)": ["Kidapawan City", "Alamada", "Aleosan", "Antipas", "Arakan", "Banisilan", "Carmen", "Kabacan", "Libungan", "Magpet", "Makilala", "Matalam", "Midsayap", "Pigkawayan", "Pikit", "President Roxas", "Tulunan"],
"Cotabato City": ["Cotabato City"],
"Sarangani": ["Alabel", "Glan", "Kiamba", "Maasim", "Maitum", "Malapatan", "Malungon"],
"South Cotabato": ["Koronadal City", "General Santos City", "Banga", "Lake Sebu", "Norala", "Polomolok", "Santo Niño", "Surallah", "T'boli", "Tampakan", "Tantangan", "Tupi"],
"Sultan Kudarat": ["Isulan", "Tacurong City", "Bagumbayan", "Columbio", "Esperanza", "Kalamansig", "Lambayong", "Lebak", "Lutayan", "Palimbang", "President Quirino", "Senator Ninoy Aquino"],
"Agusan del Norte": ["Butuan City", "Buenavista", "Cabadbaran City", "Carmen", "Jabonga", "Kitcharao", "Las Nieves", "Magallanes", "Nasipit", "Remedios T. Romualdez", "Santiago", "Tubay"],
"Agusan del Sur": ["Prosperidad", "Bayugan City", "Bunawan", "Esperanza", "La Paz", "Loreto", "Rosario", "San Francisco", "San Luis", "Santa Josefa", "Sibagat", "Talacogon", "Trento", "Veruela"],
"Dinagat Islands": ["Basilisa (Rizal)", "Cagdianao", "Dinagat", "Libjo (Albor)", "Loreto", "San Jose"],
"Surigao del Norte": ["Surigao City", "Alegria", "Bacuag", "Burgos", "Claver", "Dapa", "Del Carmen", "General Luna", "Gigaquit", "Mainit", "Malimono", "Pilar", "Placer", "San Benito", "San Francisco (Anao-Aon)", "San Isidro", "Santa Monica (Sapao)", "Sison", "Socorro", "Tagana-an", "Tubod"],
"Surigao del Sur": ["Tandag City", "Barobo", "Bayabas", "Cagwait", "Cantilan", "Carmen", "Carrascal", "Cortes", "Hinatuan", "Lanuza", "Lianga", "Lingig", "Madrid", "Marihatag", "San Agustin", "San Miguel", "Tagbina", "Tago"],
"Basilan": ["Basilan", "Isabela City", "Akbar", "Al-Barka", "Hadji Mohammad Ajul", "Hadji Muhtamad", "Lamitan", "Lantawan", "Maluso", "Sumisip", "Tabuan-Lasa", "Tipo-Tipo", "Tuburan", "Ungkaya Pukan"],
"Lanao del Sur": ["Marawi City", "Bacolod-Kalawi (Bacolod-Grande)", "Balabagan", "Balindong (Watu)", "Bayang", "Binidayan", "Buadiposo-Buntong", "Bubong", "Butig", "Calanogas", "Ditsaan-Ramain", "Ganassi", "Kapai", "Kapatagan", "Lumba-Bayabao (Maguing)", "Lumbaca-Unayan", "Lumbatan", "Lumbayanague", "Madalum", "Madamba (Dataran Sawabi)", "Maguing", "Malabang", "Marantao", "Marogong", "Masiu", "Mulondo", "Pagayawan (Tatarikan)", "Piagapo", "Picong (Sultan Gumander)", "Poona Bayabao (Gata)", "Pualas", "Saguiaran", "Sultan Dumalondong", "Picong", "Tagoloan II", "Tamparan", "Taraka", "Tubaran", "Tugaya", "Wao"],
"Maguindanao": ["Shariff Aguak (Maganoy)", "Barira", "Buldon", "Buluan", "Datu Abdullah Sangki", "Datu Anggal Midtimbang", "Datu Blah T. Sinsuat", "Datu Hoffer Ampatuan", "Datu Montawal (Pagagawan)", "Datu Odin Sinsuat (Dinaig)", "Datu Paglas", "Datu Piang (Dulawan)", "Datu Salibo", "Datu Saudi-Ampatuan", "Datu Unsay", "Gen. S. K. Pendatun", "Guindulungan", "Kabuntalan (Tumbao)", "Mamasapano", "Mangudadatu", "Matanog", "Northern Kabuntalan", "Pagalungan", "Paglat", "Pandag", "Parang", "Rajah Buayan", "Shariff Saydona Mustapha", "South Upi", "Sultan Kudarat (Nuling)", "Sultan Mastura", "Sultan sa Barongis (Lambayong)", "Talayan", "Talitay", "Upi"],
"Sulu": ["Jolo", "Banguingui (Tongkil)", "Hadji Panglima Tahil (Marunggas)", "Indanan", "Kalingalan Caluang", "Lugus", "Luuk", "Maimbung", "Old Panamao", "Omar", "Pandami", "Panglima Estino (New Panamao)", "Pangutaran", "Parang", "Pata", "Patikul", "Siasi", "Talipao", "Tapul", "Tongkil"],
"Tawi-Tawi": ["Bongao", "Languyan", "Mapun (Cagayan de Tawi-Tawi)", "Panglima Sugala (Balimbing)", "Sapa-Sapa", "Sibutu", "Simunul", "Sitangkai", "South Ubian", "Tandubas", "Turtle Islands (Taganak)"]

};

    if (selectedState in philippines_cities) {
        philippines_cities[selectedState].forEach(function(city) {
            var option = document.createElement("option");
            option.value = city;
            option.text = city;
            citySelect.appendChild(option);
        });
    } else {
        var option = document.createElement("option");
        option.value = "";
        option.text = "Select City";
        citySelect.appendChild(option);
    }
}


// function updateBarangays(selectedCity) {
//     var barangaySelect = document.getElementById("barangaySelect");
//     barangaySelect.innerHTML = "";

//     var philippines_barangays = {
//         "Munoz": ["Dapdap East", "Dapdap West", "Bantug", "Lourdes", "Llanera", "Rizal", "San Sebastian", "San Roque", "Trinidad", "Urbiztondo"]
//     };

//     if (selectedCity in philippines_barangays) {
//         philippines_barangays[selectedCity].forEach(function(barangay) {
//             var option = document.createElement("option");
//             option.value = barangay;
//             option.text = barangay;
//             barangaySelect.appendChild(option);
//         });
//     } else {
//         var option = document.createElement("option");
//         option.value = "";
//         option.text = "Select Barangay";
//         barangaySelect.appendChild(option);
//     }
// }