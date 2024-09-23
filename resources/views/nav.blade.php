<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
    <a class="navbar-brand ps-3">
      <img src="{{ asset('logo_ft.png') }}" alt="Logo" style="width: 100px; height: auto;">
    </a>
    <a class="navbar-brand" style="margin-left: 10px;">Family Tree</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item" style="margin-right: 20px;"> 
            <a class="nav-link active" aria-current="page" href="{{ route('people.index') }}">People</a>
          </li>
          <li class="nav-item" style="margin-right: 20px;"> 
            <a class="nav-link active" href="{{ route('peopleparents.index') }}">Parent</a>
          </li>
          <li class="nav-item" style="margin-right: 20px;"> 
            <a class="nav-link active" href="{{ route('peoplecouple.index') }}">Couple</a>
          </li>
          <li class="nav-item">
          <!-- Tombol Logout -->
          <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: inline;">
              @csrf
              <button type="button" id="logoutButton" class="btn btn-danger" style="margin-right: 20px;">Logout</button>
          </form>
  
          </li>
          <script>
      document.addEventListener('DOMContentLoaded', function() {
          const logoutButton = document.querySelector('#logoutButton');
          const logoutForm = document.querySelector('#logoutForm');
          if (logoutButton) {
              logoutButton.addEventListener('click', function(event) {
                  const confirmation = confirm('Are you sure you want to logout?');
                  if (confirmation) {
                      logoutForm.submit(); 
                  }
              });
          }
      });
  </script>
  
        </ul>
      </div>
    </div>
  </nav>
  