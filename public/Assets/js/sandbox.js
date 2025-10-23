const mp = new MercadoPago('TEST-a2b0a29b-5af1-4b75-ac82-d4d34bb22c4d', {locale: 'es-PE'});

const cardForm = mp.cardForm({
  amount: '100.00',
  autoMount: true,
  form: {
    id: 'form-checkout',
    cardNumber: {id: 'form-checkout__cardNumber'},
    expirationDate: {id: 'form-checkout__expirationDate'},
    securityCode: {id: 'form-checkout__securityCode'},
    installments: {id: 'form-checkout__installments'},
    issuer: {id: 'form-checkout__issuer'},
    cardholderName: {id: 'form-checkout__cardholderName'},
    cardholderEmail: {id: 'form-checkout__cardholderEmail'},
    identificationType: {id: 'form-checkout__identificationType'},
    identificationNumber: {id: 'form-checkout__identificationNumber'}
  },
  callbacks: {
    onFormMounted: error => {
      if (error) return console.warn('Error mounting:', error);
      console.log('✅ MP CardForm montado OK!');
    },
    onSubmit: event => {
      event.preventDefault();
      console.log(cardForm.getCardFormData());
    }
  }
});
