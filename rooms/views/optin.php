<div class="card ">
    <div class="card-header bg-info">
        Message
    </div>
    <div class="card-body">
        <form action="/DDWT-Eindopdracht/rooms/optin" method="POST" id="form">
            <div class="form-group">
                <label for="inputUsername">Send a message to the owner:</label>
                <textarea name="message" form ="form" placeholder="Enter your message here.." required></textarea>
            </div>
            <input type="hidden" name="username" class="form-control" value="<?= $username; ?>">
            <input type="hidden" name="id" class="form-control" value="<?=$room_id?>">
            <button type="submit" class="btn btn-info">Send</button>
        </form>

    </div>
</div>