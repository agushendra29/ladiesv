<div style="max-width: 800px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); font-family: 'Segoe UI', sans-serif;">
  <h2 style="margin-bottom: 20px; font-size: 24px; color: #333;">Create News</h2>

  <form method="post" action="save_news.php">
    
    <!-- Title -->
    <div style="margin-bottom: 20px;">
      <label for="title" style="display: block; margin-bottom: 6px; font-weight: bold;">News Title</label>
      <input type="text" id="title" name="title" required
        style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px;">
    </div>

    <!-- Date -->
    <div style="margin-bottom: 20px;">
      <label for="date" style="display: block; margin-bottom: 6px; font-weight: bold;">Date</label>
      <input type="date" id="date" name="date" required
        style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px;">
    </div>

    <!-- Category -->
    <div style="margin-bottom: 20px;">
      <label for="category" style="display: block; margin-bottom: 6px; font-weight: bold;">Category</label>
      <select id="category" name="category"
        style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px;">
        <option value="">-- Select Category --</option>
        <option value="announcement">Announcement</option>
        <option value="update">Update</option>
        <option value="event">Event</option>
      </select>
    </div>

    <!-- Content -->
    <div style="margin-bottom: 20px;">
      <label for="content" style="display: block; margin-bottom: 6px; font-weight: bold;">Content</label>
      <textarea id="content" name="content" rows="6" required
        style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px;"></textarea>
    </div>

    <!-- Submit -->
    <div style="text-align: right;">
      <button type="submit"
        style="padding: 10px 24px; background-color: #007bff; color: white; font-size: 16px; border: none; border-radius: 6px; cursor: pointer;">
        Submit
      </button>
    </div>
  </form>
</div>
