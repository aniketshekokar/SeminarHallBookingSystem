  document.querySelector('form').addEventListener('submit', function(e) {
      const start = document.querySelector('[name=start_time]').value;
      const end = document.querySelector('[name=end_time]').value;
      if (start >= end) {
          alert('End time must be after start time');
          e.preventDefault();
      }
  });
  