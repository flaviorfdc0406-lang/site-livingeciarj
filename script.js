const root = document.documentElement;
const themeToggle = document.querySelector('.theme-toggle');
const menuToggle = document.querySelector('.menu-toggle');
const menuList = document.querySelector('.menu');
const moduleCards = document.querySelectorAll('[data-module]');
const moduleDetails = document.querySelector('.module-details');
const carouselTrack = document.querySelector('[data-carousel-track]');
const carouselButtons = document.querySelectorAll('[data-carousel]');
const contactForm = document.querySelector('[data-contact-form]');
const modalTriggers = document.querySelectorAll('[data-open-modal]');
const modals = document.querySelectorAll('.modal');
const currentYear = document.querySelector('[data-year]');

currentYear.textContent = new Date().getFullYear();

// Theme toggle
const storedTheme = window.localStorage.getItem('theme');
if (storedTheme === 'dark') {
  root.setAttribute('data-theme', 'dark');
  themeToggle.innerHTML = '<span aria-hidden="true">☀️</span>';
}

themeToggle?.addEventListener('click', () => {
  const isDark = root.getAttribute('data-theme') === 'dark';
  if (isDark) {
    root.removeAttribute('data-theme');
    themeToggle.innerHTML = '<span aria-hidden="true">🌙</span>';
    window.localStorage.setItem('theme', 'light');
  } else {
    root.setAttribute('data-theme', 'dark');
    themeToggle.innerHTML = '<span aria-hidden="true">☀️</span>';
    window.localStorage.setItem('theme', 'dark');
  }
});

// Mobile menu toggle
menuToggle?.addEventListener('click', () => {
  const isOpen = menuList.getAttribute('data-open') === 'true';
  menuList.setAttribute('data-open', !isOpen);
  menuToggle.setAttribute('aria-expanded', String(!isOpen));
});

menuList?.addEventListener('click', (event) => {
  if (event.target instanceof HTMLAnchorElement) {
    menuList.removeAttribute('data-open');
    menuToggle?.setAttribute('aria-expanded', 'false');
  }
});

// Module details mapping
const moduleContent = {
  financeiro: {
    title: 'Financeiro com previsibilidade',
    description:
      'Simule boletos, envie notificações de cobrança automáticas e acompanhe gráficos de inadimplência com filtros personalizados.',
    checklist: ['Dashboard com KPIs em tempo real', 'Integração com gateways de pagamento', 'Exportação em CSV e PDF'],
  },
  reservas: {
    title: 'Reservas com confirmação automática',
    description:
      'Valide o fluxo de reservas de áreas comuns, incluindo etapas de aprovação, regras de horários e limite de convidados.',
    checklist: ['Envio de e-mails de confirmação', 'Controle de disponibilidade visual', 'Logs de auditoria detalhados'],
  },
  comunicados: {
    title: 'Comunicados multicanal',
    description:
      'Crie comunicados segmentados por torre, unidade ou perfil de usuário. A simulação mostra como os conteúdos serão distribuídos.',
    checklist: ['Templates reutilizáveis', 'Programação de envios', 'Relatórios de engajamento'],
  },
  seguranca: {
    title: 'Segurança centralizada',
    description:
      'Antecipe a experiência de integrar câmeras inteligentes e portarias remotas, com alertas instantâneos para ocorrências.',
    checklist: ['Mapa tático em tempo real', 'Controle de acesso por biometria', 'Registro de incidentes'],
  },
};

moduleCards.forEach((card) => {
  card.addEventListener('click', () => {
    const key = card.dataset.module;
    if (!key) return;
    const module = moduleContent[key];
    if (!module) return;

    moduleCards.forEach((item) => item.classList.remove('module-card--active'));
    card.classList.add('module-card--active');

    moduleDetails.innerHTML = `
      <h3>${module.title}</h3>
      <p>${module.description}</p>
      <ul>${module.checklist.map((item) => `<li>${item}</li>`).join('')}</ul>
    `;
  });
});

// Carousel interaction
let currentSlide = 0;

const moveCarousel = (direction) => {
  const slides = carouselTrack.querySelectorAll('.testimonial');
  if (!slides.length) return;
  currentSlide = (currentSlide + direction + slides.length) % slides.length;
  carouselTrack.style.transform = `translateX(-${currentSlide * (slides[0].clientWidth + 24)}px)`;
};

carouselButtons.forEach((button) => {
  button.addEventListener('click', () => {
    const direction = button.dataset.carousel === 'next' ? 1 : -1;
    moveCarousel(direction);
  });
});

let carouselInterval = window.setInterval(() => moveCarousel(1), 5000);

carouselTrack?.addEventListener('pointerenter', () => {
  window.clearInterval(carouselInterval);
});

carouselTrack?.addEventListener('pointerleave', () => {
  carouselInterval = window.setInterval(() => moveCarousel(1), 5000);
});

// Contact form validation simulation
const validators = {
  nome: (value) => value.trim().length >= 2 || 'Informe pelo menos 2 caracteres.',
  email: (value) => /\S+@\S+\.\S+/.test(value) || 'Informe um e-mail válido.',
  mensagem: (value) => value.trim().length >= 10 || 'Descreva com mais detalhes sua mensagem.',
};

const showFieldError = (field, message) => {
  const errorElement = contactForm?.querySelector(`[data-error-for="${field.name}"]`);
  if (!errorElement) return;
  errorElement.textContent = message === true ? '' : message;
};

contactForm?.addEventListener('submit', (event) => {
  event.preventDefault();
  let isValid = true;

  Array.from(contactForm.elements).forEach((element) => {
    if (!(element instanceof HTMLInputElement || element instanceof HTMLTextAreaElement)) return;
    const validator = validators[element.name];
    if (!validator) return;
    const result = validator(element.value);
    showFieldError(element, result);
    if (result !== true) {
      isValid = false;
    }
  });

  const feedback = contactForm.querySelector('.form__feedback');
  if (!feedback) return;
  if (isValid) {
    feedback.textContent = 'Fluxo validado! Em produção, este formulário encaminhará a mensagem para o time responsável.';
    feedback.style.color = '#22c55e';
  } else {
    feedback.textContent = 'Existem campos com informações pendentes. Ajuste e tente novamente.';
    feedback.style.color = '#ef4444';
  }
});

Array.from(contactForm?.elements || []).forEach((element) => {
  if (!(element instanceof HTMLInputElement || element instanceof HTMLTextAreaElement)) return;
  element.addEventListener('blur', () => {
    const validator = validators[element.name];
    if (!validator) return;
    const result = validator(element.value);
    showFieldError(element, result);
  });
});

// Modal controls
const closeModals = () => {
  modals.forEach((modal) => {
    if (modal.open) {
      modal.close();
    }
  });
};

modalTriggers.forEach((trigger) => {
  trigger.addEventListener('click', (event) => {
    event.preventDefault();
    const modal = document.querySelector(`.modal[data-modal="${trigger.dataset.openModal}"]`);
    modal?.showModal();
  });
});

modals.forEach((modal) => {
  modal.addEventListener('cancel', (event) => {
    event.preventDefault();
    modal.close();
  });

  modal.addEventListener('click', (event) => {
    if (event.target instanceof HTMLDialogElement) {
      modal.close();
    }
  });

  modal.querySelectorAll('[data-close-modal]').forEach((button) => {
    button.addEventListener('click', () => modal.close());
  });
});

window.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') {
    closeModals();
  }
});
