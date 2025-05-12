    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    document.getElementById("toggleSidebar").addEventListener("click", function() {
        if (window.innerWidth >= 992) {
            document.getElementById("sidebar").classList.toggle("closed");
            document.getElementById("content").classList.toggle("shift");
    
            // Scroll engellenmişse düzelt
            if (document.body.style.overflow === "hidden") {
                document.body.style.overflow = "auto";
            }
        } else {
            var mobileSidebar = new bootstrap.Offcanvas(document.getElementById("mobileSidebar"));
            mobileSidebar.show();
        }
    });

    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Masaüstü ve mobil menülerindeki collapsed öğelerini al
        const listItemsDesktop = document.querySelectorAll('.sidebar .collapsed');
        const listItemsMobile = document.querySelectorAll('.offcanvas .collapsed');
    
        // Ortak işlevsellik için iki menüdeki listeleri birleştiriyoruz
        const listItems = [...listItemsDesktop, ...listItemsMobile];
    
        listItems.forEach(function(li) {
            // Yeni bir icon öğesi oluştur
            const icon = document.createElement('i');
            icon.className = 'fa-solid fa-chevron-down sag'; // Ok simgesi aşağıya dönük olacak
    
            // List öğesinin sonuna ekle
            li.appendChild(icon);
    
            // Her bir list item için collapse ile ilgili olayları dinle
            const targetSubmenu = document.querySelector(li.dataset.bsTarget);
            
            // İlk açılışta menü durumunu kontrol et ve ok simgesini ona göre ayarla
            if (targetSubmenu && targetSubmenu.classList.contains('show')) {
                icon.style.display = 'inline'; // Menü açıkken ok simgesini göster
            } else {
                icon.style.display = 'none'; // Menü kapalıyken ok simgesini gizle
            }
    
            // Collapse olayı dinle (Bootstrap'in collapse davranışını dinleyelim)
            $(targetSubmenu).on('show.bs.collapse', function() {
                icon.style.display = 'inline'; // Menü açıldığında ok simgesini göster
            });
    
            $(targetSubmenu).on('hide.bs.collapse', function() {
                icon.style.display = 'none'; // Menü kapandığında ok simgesini gizle
            });
        });
    });
    </script>
    
    
    <script>
    
    var c = document.getElementById("c");
    var ctx = c.getContext("2d");
    
    //making the canvas full screen
    c.height = window.innerHeight;
    c.width = window.innerWidth;
    
    //english characters
    var english = "2002020202220202020202020020202000202022202220202020220202020202020220000202";
    //converting the string into an array of single characters
    english = english.split("");
    
    var font_size = 15;
    var columns = c.width/font_size; //number of columns for the rain
    //an array of drops - one per column
    var drops = [];
    //x below is the x coordinate
    //1 = y co-ordinate of the drop(same for every drop initially)
    for(var x = 0; x < columns; x++)
        drops[x] = 1; 
    
    //drawing the characters
    function draw()
    {
        //Black BG for the canvas
        //translucent BG to show trail
        ctx.fillStyle = "rgba(0, 0, 0, 0.05)";
        ctx.fillRect(0, 0, c.width, c.height);
        
        ctx.fillStyle = "#0F0"; //green text
        ctx.font = font_size + "px arial";
        //looping over drops
        for(var i = 0; i < drops.length; i++)
        {
            //a random chinese character to print
            var text = english[Math.floor(Math.random()*english.length)];
            //x = i*font_size, y = value of drops[i]*font_size
            ctx.fillText(text, i*font_size, drops[i]*font_size);
            
            //sending the drop back to the top randomly after it has crossed the screen
            //adding a randomness to the reset to make the drops scattered on the Y axis
            if(drops[i]*font_size > c.height && Math.random() > 0.975)
                drops[i] = 0;
            
            //incrementing Y coordinate
            drops[i]++;
        }
    }
    
    setInterval(draw, 33);
    </script>
    
    <script>
        //bildirim kapatma scripti
        function removeListItem(button) {
          // Butonun bulunduğu <li> elemanını bul ve kaldır
          const listItem = button.closest('li');
          if (listItem) {
            listItem.remove();
          }
        }
    </script>

</body>
</html>