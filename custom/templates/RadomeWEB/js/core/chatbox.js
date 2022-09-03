chatbox.templates = {

    container: `
		<div class="card card-chatbox" id="chatbox" data-position="{position}">
			<div class="card-header header-theme">
				{title}
			</div>
			<div class="card-body">
				<div class="list chat-list" id="chatLog">
					{loading}
				</div>
			</div>
			<div class="card-footer">
				<form action="" class="disabled" id="chatForm" autocomplete="off">
					<div class="input-group">
						   <input type="text" class="form-control" id="chatInput" placeholder="{messagePlaceholder}" />
						   <span class="input-group-btn">
								<button type="submit" class="btn btn-theme float-right" id="chatInputSubmit">
									<i class="fas fa-share fa-fw"></i>
								</button>
						   </span>
					</div>
				</form>
			</div>
		</div>
	`,

    item: `
		<div class="row chat-item" id="chatMessage-{messageId}">
			<div class="col-2 col-md-1">
				<a href="{authorProfile}"><img src="{authorAvatar}" class="avatar-img" style="max-width: 100%"></a>
			</div>
			<div class="col-10 col-md-11">
				<a href="{authorProfile}" style="{authorStyle}">{authorUsername}</a><div class="float-right">{messageActions}</div>
				<small>{messageTime}</small><br />{messageContent}
			</div>
		</div>
	`
};

chatbox.elements = {
    containerTop: '#chatbox-top',
    containerBottom: '#chatbox-bottom',
    root: '#chatbox',
    log: '#chatbox #chatLog',
    form: '#chatbox #chatForm',
    input: '#chatbox #chatInput'
};

chatbox.style = `

`;

document.addEventListener('DOMContentLoaded', () => {
    chatbox.instance = new Chatbox(chatbox);
});