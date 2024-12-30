'use strict';

$(document).ready(function () {
  // Set the selected value of category filter from URL
  const urlParams = new URLSearchParams(window.location.search);
  const categorySlug = urlParams.get('category');
  const searchQuery = urlParams.get('search');

  if (categorySlug) {
    $('#categoryFilter').val(categorySlug);
  }

  // Set search input to value from URL
  if (searchQuery) {
    $('#searchInput').val(searchQuery);
  }

  // Handle pagination clicks
  $(document).on('click', '.pagination a', function (e) {
    e.preventDefault();

    const page = $(this).attr('href').split('page=')[1];
    const categorySlug = $('#categoryFilter').val();
    const searchQuery = $('#searchInput').val();

    let newUrl = `${window.location.pathname}?page=${page}`;
    if (categorySlug) newUrl += `&category=${categorySlug}`;
    if (searchQuery) newUrl += `&search=${searchQuery}`;
    window.location.href = newUrl;
  });

  // Handle search button click
  $('#searchButton').on('click', function () {
    const searchQuery = $('#searchInput').val();
    const categorySlug = $('#categoryFilter').val();

    if (searchQuery.trim() === '') {
      alert('Please enter a search term.');
      return;
    }

    let newUrl = `${window.location.pathname}?page=1`;
    if (categorySlug) newUrl += `&category=${categorySlug}`;
    if (searchQuery) newUrl += `&search=${searchQuery}`;
    window.location.href = newUrl;
  });

  // Handle category filter change
  $('#categoryFilter').on('change', function () {
    const categorySlug = $(this).val();
    const searchQuery = $('#searchInput').val();

    let newUrl = `${window.location.pathname}?page=1`;
    if (categorySlug) newUrl += `&category=${categorySlug}`;
    if (searchQuery) newUrl += `&search=${searchQuery}`;
    window.location.href = newUrl;
  });
  
  $('#resetButton').on('click', function () {
    window.location.href = window.location.pathname; // Reset to base URL
  });
});
