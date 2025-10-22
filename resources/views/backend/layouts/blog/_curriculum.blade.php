<div class="row" id="user-profile">
    <div class="col-lg-12">
        <div class="card post-sales-main">
            <div class="card-header border-bottom">
                <h3 class="card-title mb-0">Curriculum</h3>
                <div class="card-options">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">Add</button>
                </div>
            </div>
            <div class="card-body border-0">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach(App\Models\User::all() as $user)
                  <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $user->name }}</td>
                    <td>
                      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="{{ $user->name }}" data-id="{{ $user->id }}">Edit</button>
                      <a href="#" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                  </tr>
                  @endforeach
                <tbody>
              </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">New message</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="curriculum-form" action="" method="POST">
      @csrf
        <div class="modal-body">
            <div class="mb-3">
              <label for="recipient-name" class="col-form-label">Recipient:</label>
              <input type="text" class="form-control" id="recipient-name">
            </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Send message</button>
      </div>
    </div>
  </div>
</div>

<script>
const exampleModal = document.getElementById('exampleModal')
if (exampleModal) {
  exampleModal.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const button = event.relatedTarget
    // Extract info from data-bs-* attributes
    const recipient = button.getAttribute('data-bs-whatever')
    const id = button.getAttribute('data-id')
    if(id){
      document.getElementById('curriculum-form').action = '/curriculum/' + id + '/update';
    } else {
      document.getElementById('curriculum-form').action = '/curriculum/store';
    }
    const modalTitle = exampleModal.querySelector('.modal-title')
    const modalBodyInput = exampleModal.querySelector('.modal-body input')
    modalTitle.textContent = `New message to ${recipient}`
    modalBodyInput.value = recipient
  })
}
</script>