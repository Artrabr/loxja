function removeWrapper(something) {
  const cartItem = something.closest('.cart_item_wrapper');
  if (cartItem) {
    cartItem.remove();
  }
}

// TODO: checar isso
function sendForm() {
  const formElement = document.getElementById('shopping_cart')

  // 1. Manually pull data right from the form element
  const formData = new FormData(formElement)

  // 2. Send via Fetch API
  try {
    const response = await fetch('process_order.php', {
      method: 'POST',
      body: formData
    })

    const result = await response.text()
    console.log('Success:', result)
  } catch (error) {
    console.error('Error:', error)
  }
}

let total_price = 0

document.querySelectorAll('.price_full').forEach(span => {
  total_price += parseFloat(span.textContent)
})
document.getElementById("preco_final").textContent = total_price.toFixed(2)

class CartItem {
  #value;
  #amount;
  #limit;
  #past_zero;
  #container;
  #price;
  #price_per_product;

  constructor(container) {
    this.#amount = container.querySelector('.amount_of_products');
    this.#value = parseInt(this.#amount.value) || 0;
    this.#limit = parseInt(container.querySelector('.increase').getAttribute('estoque')) || 0;
    this.#past_zero = false;
    this.#container = container;
    this.#price = container.closest('.cart_item_wrapper').querySelector('.price_full');
    this.#price_per_product = parseFloat(container.querySelector('.increase').getAttribute('price')) || 0;
  }

  get value() {
    this.#value = parseInt(this.#amount.value) || 0;
    return this.#value;
  }

  set value(v) {
    if (v < 0) {
      if (this.#past_zero) {
        removeWrapper(this.#container)
        return
      }
      this.#amount.value = this.#value
      this.#value = 0;
      setTimeout(() => {
        this.#past_zero = true;
      }, 500);
      return
    }
    this.#value = v ?? 0;
    if (this.#value > this.#limit) {
      this.#amount.value = this.#limit
      this.#value = this.#limit
    } else {
      this.#amount.value = this.#value;
    }
    this.#price.innerText = (this.#price_per_product * this.#value).toFixed(2)

    total_price = 0
    document.querySelectorAll('.price_full').forEach(span => {
      total_price += parseFloat(span.textContent)
    })
    document.getElementById("preco_final").textContent = total_price.toFixed(2)
  }
}

// Usage
document.querySelectorAll('.shopping_cart .cart_item_actions').forEach(div => {
  const item = new CartItem(div);
  const increase = div.querySelector('.increase')
  const decrease = div.querySelector('.decrease')

  /** @type {HTMLInputElement | null} */
  const amount_of_products = div.querySelector('.amount_of_products')
  const limit = increase.getAttribute('estoque')

  const remove = div.querySelector('.remove')

  amount_of_products.addEventListener('input', (event) => {
    item.value = event.target.value
  });

  increase.addEventListener('click', () => {
    item.value = item.value + 1
  });

  decrease.addEventListener('click', () => {
    item.value = item.value - 1
  });

  remove.addEventListener('click', () => {
    item.value = 0
    removeWrapper(div)
  });

});

document.addEventListener('keydown', function (event) {
  // Check if the pressed key is 'Enter' AND the target element has our specific class
  if (event.key === 'Enter' && event.target.classList.contains('amount_of_products')) {
    // Stop the default browser action (which is submitting the form)
    event.preventDefault();

    // Optional: You can trigger something else here, like moving focus to the next field
    console.log("Enter key submission blocked for this class!");
  }
});
