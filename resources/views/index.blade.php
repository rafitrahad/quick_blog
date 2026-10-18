<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quick Blog - Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        /* Navbar */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .navbar-brand {
            color: #0d6efd;
            font-size: 25px;
            font-weight: bold;
        }

        /* Main content */
        .main-container {
            max-width: 850px;
            margin: 40px auto;
        }

        /* Blog post */
        .post-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .post-title {
            font-size: 28px;
            font-weight: bold;
            color: #212529;
        }

        .post-meta {
            color: #777;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .post-content {
            font-size: 16px;
            line-height: 1.8;
            color: #444;
        }

        /* Comment section */
        .comment-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .comment-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            overflow-wrap: anywhere;
        }

        .comment-author {
            font-weight: bold;
            color: #0d6efd;
        }

        .comment-date {
            color: #888;
            font-size: 12px;
        }

        .footer {
            text-align: center;
            color: #888;
            padding: 25px;
        }
    </style>
</head>

<body>

    <!-- ================= NAVBAR ================= -->

    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <a class="navbar-brand" href="{{ route('home') }}">
                Quick Blog
            </a>

            <div class="d-flex align-items-center gap-3">

                <a href="{{ route('home') }}"
                   class="text-decoration-none text-dark">
                    Home
                </a>

                @auth
                    <span class="text-secondary">
                        Hi, {{ auth()->user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf

                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('loginView') }}"
                       class="btn btn-outline-primary btn-sm">
                        Login
                    </a>

                    <a href="{{ url('/signup') }}"
                       class="btn btn-primary btn-sm">
                        Sign Up
                    </a>
                @endauth

            </div>
        </div>
    </nav>


    <!-- ================= MAIN CONTENT ================= -->

    <div class="container main-container">

        <!-- Welcome Message -->

        <div class="mb-4">
            <h2 class="fw-bold">Welcome to Quick Blog 👋</h2>

            <p class="text-secondary">
                Read interesting posts and share your thoughts with others.
            </p>
        </div>


        <!-- ================= BLOG POST ================= -->

        <div class="post-card">

            <h1 class="post-title">
                Welcome to the World of Laravel
            </h1>

            <div class="post-meta">
                <span>By Admin</span>
                <span class="mx-2">|</span>
                <span>October 18, 2026</span>
                <span class="mx-2">|</span>
                <span>Programming</span>
            </div>

            <div class="post-content">

                <p>
                    Laravel is a powerful PHP framework that makes web
                    application development easier and more enjoyable.
                    It provides tools for routing, database management,
                    authentication, and much more.
                </p>

                <p>
                    When building a blog website, Laravel helps us organize
                    our code using routes, controllers, models, and views.
                    These components work together to create dynamic web pages.
                </p>

                <p>
                    As you learn Laravel, start with the basics and gradually
                    build more advanced features. You can create user accounts,
                    publish blog posts, and allow users to interact through
                    comments.
                </p>

                <p class="mb-0">
                    <strong>
                        Keep learning, keep building, and keep improving!
                    </strong>
                </p>

            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center">

                <span class="text-secondary">
                    📝 Sample Blog Post
                </span>

                <span class="badge bg-primary">
                    Laravel
                </span>

            </div>

        </div>


        <!-- ================= COMMENT SECTION ================= -->

        <div class="comment-card">

            <h4 class="fw-bold mb-2">
                Comments
            </h4>

            <p class="text-secondary">
                Share your thoughts about this post.
            </p>

            <!-- Comment Form -->

            <form id="commentForm">

                <div class="mb-3">

                    <label for="comment" class="form-label">
                        Write a comment
                    </label>

                    <textarea
                        id="comment"
                        class="form-control"
                        rows="4"
                        placeholder="What do you think about this post?"
                        required
                    ></textarea>

                </div>

                <button type="submit" class="btn btn-primary">
                    Submit Comment
                </button>

            </form>


            <hr class="my-4">


            <!-- Comments will appear here -->

            <div id="commentsList">

                <h5 class="fw-semibold mb-3">
                    Recent Comments
                </h5>

                <div class="comment-item">

                    <div class="d-flex justify-content-between">

                        <span class="comment-author">
                            Rahim
                        </span>

                        <span class="comment-date">
                            October 18, 2026
                        </span>

                    </div>

                    <p class="mb-0 mt-2">
                        This is a great introduction to Laravel!
                    </p>

                </div>


                <div class="comment-item">

                    <div class="d-flex justify-content-between">

                        <span class="comment-author">
                            Karim
                        </span>

                        <span class="comment-date">
                            October 18, 2026
                        </span>

                    </div>

                    <p class="mb-0 mt-2">
                        I am excited to learn more about Laravel.
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- ================= FOOTER ================= -->

    <footer class="footer">
        <p class="mb-0">
            &copy; 2026 Quick Blog. All rights reserved.
        </p>
    </footer>


    <!-- ================= JAVASCRIPT ================= -->

    <script>
        const commentForm = document.getElementById('commentForm');
        const commentInput = document.getElementById('comment');
        const commentsList = document.getElementById('commentsList');

        commentForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const commentText = commentInput.value.trim();

            if (commentText === '') {
                return;
            }

            // Create a new comment container
            const commentItem = document.createElement('div');
            commentItem.className = 'comment-item';

            // Create the comment author
            const author = document.createElement('div');
            author.className = 'comment-author';
            author.textContent = 'Guest';

            // Create the comment text
            const paragraph = document.createElement('p');
            paragraph.className = 'mb-0 mt-2';
            paragraph.textContent = commentText;

            // Add the current date
            const date = document.createElement('div');
            date.className = 'comment-date';
            date.textContent = new Date().toLocaleDateString();

            // Add elements to the comment
            const header = document.createElement('div');
            header.className = 'd-flex justify-content-between';

            header.appendChild(author);
            header.appendChild(date);

            commentItem.appendChild(header);
            commentItem.appendChild(paragraph);

            // Add the new comment to the page
            commentsList.appendChild(commentItem);

            // Clear the input field
            commentInput.value = '';
        });
    </script>

</body>
</html>