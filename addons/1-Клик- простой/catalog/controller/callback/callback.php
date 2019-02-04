<div id="order-form">
	<form>
		<div class="header">
			<h4 class="title">Заказ товара</h4>
			<div class="close-button"></div>
		</div>
		<div class="content">
			<div class="top">
				<img class="img" src="" alt="">
				<div class="calculator">
					<div class="name"></div>
					<div class="value">
						<span class="text">Количество:</span>
						<button class="minus" type="button">-</button>
						<input name="value" type="number" value="1" min="1">
						<button class="plus" type="button">+</button>
					</div>
					<div class="sum-block">
						<span class="text">Ваша цена:</span>
						<span class="sum"></span>
						<span class="curency"></span>
					</div>
				</div>
			</div>
			<div class="contacts">
				<div class="input-group">
					<label class="required">Имя:</label>
					<input name="name" placeholder="Имя Отчество Фамилия" required>
				</div>
				<div class="input-group">
					<label class="required">Телефон:</label>
					<input name="phone" placeholder="+7 (___) ___-__-__" required>
				</div>
				<div class="input-group">
					<label>E-mail:</label>
					<input name="email" placeholder="">
				</div>
				<div class="input-group">
					<label>Комментарий:</label>
					<textarea name="comment" placeholder=""></textarea>
				</div>
				
				<div class="button-wrapper">
					<button class="btn btn-danger" type="submit">Отправить заказ</button>
				</div>
				
				<p class="disclamer">Нажимая на кнопку "Отправить заказ" я даю свое согласие на обработку персональных данных и принимаю <a href="/disclamer" target="_blank">условия соглашения</a>.</p>
			</div>
		</div>
	</form>
</div>
