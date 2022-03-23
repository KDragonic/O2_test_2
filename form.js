function validate(form)
{
	let errors = false;
	let inputs = $(form).find("input");
	for (let input of inputs) if (input.hasAttribute('required'))
	{
		if (input.val == "")
		{
			setTextErrors(input.attr("id"), "Заполните это поле");
			errors = true;
		}
	}
	let patternEmail = /^[a-z0-9_-]+@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{2,6}$/i;
	if ($("#email").val().search(patternEmail) !== 0)
	{
		setTextErrors("mail", "Неверный формат");
		errors = true;
	}
	return !errors;
}

function setTextErrors(input, text)
{
	$("#" + input).after("<p>" + text + "</p>");
	console.error(input, text);
}

function send(event)
{
	event.preventDefault();
	let form = event.target;
	if (validate(form))
	{
		$.ajax({
			url: '/form.php',
			method: 'post',
			dataType: 'json',
			data: $(form).serialize(),
			success: function(data)
			{
				if (data?.errors)
				{
					console.log(data.errors);
					data.errors.forEach(error =>
					{
						setTextErrors(error, data.message);
					});
				}
				else if (data.message == "Запрос выполнен удачно!") SuccessfulExecution();
                else if (data.massage == "Такая запись уже существует") setTextErrors("email", "Подписка с такой почтой есть");
			},
			// error: function(data) { }
		});
	}
}

function SuccessfulExecution()
{
	alert("Вы успешно подписались");
	$("input").val("");
};