// Fungsi untuk menambahkan class 'show' saat elemen muncul di viewport
function onScroll() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        const rect = item.getBoundingClientRect();
        if (rect.top < window.innerHeight) {
            item.classList.add('show');
        }
    });

    const packages = document.querySelectorAll('.package-item');
    packages.forEach(package => {
        const packagePosition = package.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.2;

        if (packagePosition < screenPosition) {
            package.style.opacity = '1';
            package.style.transform = 'translateY(0)';
        }
    });
}

// Tambahkan event listener untuk scroll
window.addEventListener('scroll', onScroll);

// Jalankan fungsi onScroll saat halaman dimuat
document.addEventListener('DOMContentLoaded', onScroll);

document.addEventListener('DOMContentLoaded', () => {
    const galleryItems = document.querySelectorAll('.gallery-item');

    function checkVisibility() {
        galleryItems.forEach(item => {
            const rect = item.getBoundingClientRect();
            const isVisibleFromBottom = (rect.top <= window.innerHeight && rect.bottom >= 0);
            const isVisibleFromTop = (rect.bottom >= 0 && rect.top <= window.innerHeight);
            if (isVisibleFromBottom || isVisibleFromTop) {
                item.classList.add('show');
            } else {
                item.classList.remove('show');
            }
        });
    }

    window.addEventListener('scroll', checkVisibility);
    window.addEventListener('resize', checkVisibility);
    checkVisibility(); // Jalankan fungsi saat halaman dimuat

    // Fungsi untuk menambahkan animasi muncul dari bawah
    const packages = document.querySelectorAll('.package-item');
    packages.forEach(package => {
        package.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
        package.style.transform = 'translateY(100px)';
        package.style.opacity = '0';
    });

    function showPackagesOnScroll() {
        packages.forEach(package => {
            const packagePosition = package.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.2;

            if (packagePosition < screenPosition) {
                package.style.opacity = '1';
                package.style.transform = 'translateY(0)';
            }
        });
    }

    window.addEventListener('scroll', showPackagesOnScroll);
    window.addEventListener('resize', showPackagesOnScroll);
    showPackagesOnScroll(); // Jalankan fungsi saat halaman dimuat
});

document.addEventListener('DOMContentLoaded', function () {
    const orderButtons = document.querySelectorAll('.order-btn');
    const packageNameInput = document.getElementById('package-name');
    const priceInput = document.getElementById('price');

    orderButtons.forEach(button => {
        button.addEventListener('click', function () {
            const packageCard = this.closest('.package-card');
            const packageName = packageCard.getAttribute('data-package');
            const price = packageCard.getAttribute('data-price');

            packageNameInput.value = packageName;
            priceInput.value = price;
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const galleryItems = document.querySelectorAll('.gallery-item');

    const showItems = () => {
        galleryItems.forEach((item, index) => {
            setTimeout(() => {
                item.classList.add('show');
            }, index * 100); // Tambahkan sedikit delay untuk setiap item
        });
    };

    const handleScroll = () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;

        galleryItems.forEach(item => {
            const itemTop = item.getBoundingClientRect().top + scrollTop;
            if (scrollTop + windowHeight > itemTop) {
                item.classList.add('show');
            }
        });
    };

    window.addEventListener('scroll', handleScroll);
    handleScroll();
});

document.addEventListener('DOMContentLoaded', function () {
    const galleryItems = document.querySelectorAll('.gallery-item');

    function checkVisibility() {
        const triggerBottom = window.innerHeight / 5 * 4;

        galleryItems.forEach(item => {
            const itemTop = item.getBoundingClientRect().top;

            if (itemTop < triggerBottom) {
                item.classList.add('visible');
            } else {
                item.classList.remove('visible');
            }
        });
    }

    window.addEventListener('scroll', checkVisibility);
    checkVisibility();
});

document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.gallery-item');

    function checkVisibility() {
        const triggerBottom = window.innerHeight / 5 * 4;

        items.forEach(item => {
            const itemTop = item.getBoundingClientRect().top;

            if (itemTop < triggerBottom) {
                item.classList.add('visible');
            } else {
                item.classList.remove('visible');
            }
        });
    }

    window.addEventListener('scroll', checkVisibility);
    checkVisibility();
});

document.addEventListener('DOMContentLoaded', function () {
    const galleryItems = document.querySelectorAll('.gallery-item');

    const showItems = () => {
        galleryItems.forEach((item, index) => {
            setTimeout(() => {
                item.classList.add('show');
            }, index * 100); // Tambahkan sedikit delay untuk setiap item
        });
    };

    const handleScroll = () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;

        galleryItems.forEach(item => {
            const itemTop = item.getBoundingClientRect().top + scrollTop;
            if (scrollTop + windowHeight > itemTop) {
                item.classList.add('show');
            }
        });
    };

    window.addEventListener('scroll', handleScroll);
    handleScroll();
});


document.addEventListener("DOMContentLoaded", function () {
    const notificationIcon = document.querySelector(".notification-icon");
    const notificationContainer = document.querySelector(".notification-container");

    notificationIcon.addEventListener("click", function () {
        if (notificationContainer.classList.contains('hidden')) {
            notificationContainer.classList.remove('hidden');
            notificationContainer.style.right = "0";
            // Ambil notifikasi dari database
            fetchNotifications();
        } else {
            notificationContainer.style.right = "-300px"; // Ubah ke nilai yang sesuai untuk menutup kontainer
            setTimeout(() => {
                notificationContainer.classList.add('hidden');
            }, 500); // Tambahkan delay sebelum menyembunyikan notifikasi
        }
    });

    // Tambahkan event listener untuk tombol close (tombol "x") di dalam kontainer notifikasi
    const closeBtn = document.getElementById('close-notification');
    closeBtn.addEventListener('click', () => {
        notificationContainer.style.right = "-300px"; // Menutup kontainer notifikasi dengan animasi slide-out
        setTimeout(() => {
            notificationContainer.classList.add('hidden');
        }, 500); // Tambahkan delay sebelum menyembunyikan notifikasi
    });
});

document.querySelectorAll('.order-btn').forEach(button => {
    button.addEventListener('click', function() {
        const packageCard = this.closest('.package-card');
        const packageName = packageCard.getAttribute('data-package');
        const packagePrice = packageCard.getAttribute('data-price');
        document.getElementById('package-name').value = packageName;
        document.getElementById('price').value = packagePrice;
    });
});

// Mendapatkan semua tautan pada navbar
const links = document.querySelectorAll('.nav-links a');

// Menambahkan event listener untuk setiap tautan
links.forEach(function(link) {
    link.addEventListener('click', function() {
        // Menghapus kelas 'active' dari semua tautan
        links.forEach(function(item) {
            item.classList.remove('active');
        });
        // Menambahkan kelas 'active' ke tautan yang diklik
        this.classList.add('active');
    });
});

function loadVendors() {
    const packageName = document.getElementById('package-name').value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "get_vendors.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            document.getElementById('vendor').value = response.vendorNames;
        }
    };
    xhr.send("packageID=" + encodeURIComponent(packageName));
}

document.querySelectorAll('.feedback-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(form);
        fetch('process_feedback.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Feedback berhasil dikirim!');
                form.reset(); // Reset form after feedback is submitted
            } else {
                alert('Gagal mengirim feedback: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim feedback.');
        });
    });
});
 document.addEventListener('DOMContentLoaded', () => {
    const notificationIcon = document.querySelector('.notification-icon');
    const notificationContainer = document.querySelector('#notification-container');
    const closeNotificationButton = document.querySelector('#close-notification');

    // Toggle notification container visibility with notification icon
    notificationIcon.addEventListener('click', (event) => {
        event.stopPropagation();
        notificationContainer.classList.toggle('hidden');
    });

    // Close notification container only when clicking the close button
    closeNotificationButton.addEventListener('click', (event) => {
        event.stopPropagation();
        notificationContainer.classList.add('hidden');
    });

    // Prevent other clicks inside the notification container from closing it
    notificationContainer.addEventListener('click', (event) => {
        event.stopPropagation();
    });

    // Close notification container if clicking outside of it
    document.addEventListener('click', () => {
        notificationContainer.classList.add('hidden');
    });
});

