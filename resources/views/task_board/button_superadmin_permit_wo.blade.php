  <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
      <div class="btn-group" role="group">
          <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
              aria-expanded="false">
              Action
          </button>
          <ul class="dropdown-menu">
              <li>
                  <form class="d-inline"
                      action="{{ route('task_board.cancel', ['assignee' => 'sales-admin', 'id' => $d->id, 'doc_type' => 'work-order']) }}"
                      method="POST" id="form-cancel{{ $d->id }}">
                      @csrf
                      @method('PUT')
                      <a class="dropdown-item" href="#" href="#" role="button"
                          onclick="cancel({{ $d->id }}); return false;">Cancel</a>
                      </a>
                  </form>
              </li>
              <li>
                  <form class="d-inline"
                      action="{{ route('task_board.delete', ['assignee' => 'sales-admin', 'id' => $d->id, 'doc_type' => 'work-order']) }}"
                      method="POST" id="form-delete{{ $d->id }}">
                      @csrf
                      @method('DELETE')
                      <a class="dropdown-item" href="#" href="#" role="button"
                          onclick="delete_data({{ $d->id }}); return false;">Delete</a>
                      </a>
                  </form>
              </li>
          </ul>
      </div>
  </div>
