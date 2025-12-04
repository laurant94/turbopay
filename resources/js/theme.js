// Contains a set of helper functions for managing the theme
const ThemeManager = {
  // Returns the current theme from local storage
  getTheme() {
    return localStorage.getItem('theme');
  },

  // Sets the current theme in local storage
  setTheme(theme) {
    localStorage.setItem('theme', theme);
  },

  // Applies the current theme to the document
  applyTheme() {
    const theme = this.getTheme();
    if (theme === 'dark') {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  },

  // Toggles the current theme
  toggleTheme() {
    const theme = this.getTheme();
    if (theme === 'dark') {
      this.setTheme('light');
    } else {
      this.setTheme('dark');
    }
    this.applyTheme();
  },

  // Initializes the theme
  init() {
    this.applyTheme();
  },
};

ThemeManager.init();

window.ThemeManager = ThemeManager;
