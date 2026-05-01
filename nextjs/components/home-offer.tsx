export function HomeOffer() {
  return (
    <section className="offer-section" id="about">
      <div className="container">
        <h2>What Can We Offer?</h2>
        <p className="section-subtitle">
          At our tuition center, we offer comprehensive IT tuition services tailored to your needs. We&apos;re
          committed to empowering you to excel in the ever-evolving world of technology.
        </p>
        <div className="offer-grid">
          <div className="offer-item">
            <i className="fas fa-code" />
            <h4>Programming Languages</h4>
            <p>Learn popular languages like Python, Java, C#, and JavaScript from expert instructors.</p>
          </div>
          <div className="offer-item">
            <i className="fas fa-laptop-code" />
            <h4>Software Development</h4>
            <p>Master the principles of software engineering and build robust applications.</p>
          </div>
          <div className="offer-item">
            <i className="fas fa-database" />
            <h4>Database Management</h4>
            <p>Gain expertise in SQL, NoSQL, and database administration for efficient data handling.</p>
          </div>
          <div className="offer-item">
            <i className="fas fa-globe" />
            <h4>Web Development</h4>
            <p>Create dynamic and responsive websites using front-end and back-end technologies.</p>
          </div>
          <div className="offer-item">
            <i className="fas fa-chart-line" />
            <h4>Skill Enhancement &amp; Career Growth</h4>
            <p>From beginners to seasoned pros, we help you kickstart your journey or advance your skills.</p>
          </div>
          <div className="offer-item">
            <i className="fas fa-user-graduate" />
            <h4>Hands-on &amp; Personalized Learning</h4>
            <p>Benefit from practical training, individual attention, and flexible scheduling options.</p>
          </div>
          <div className="offer-item">
            <i className="fas fa-cloud" />
            <h4>Cloud &amp; DevOps</h4>
            <p>Deploy and manage applications in the cloud with modern DevOps practices and tools.</p>
          </div>
          <div className="offer-item">
            <i className="fas fa-shield-alt" />
            <h4>Cybersecurity &amp; IT Security</h4>
            <p>Learn to protect systems and data with security best practices and defensive techniques.</p>
          </div>
        </div>
        <a className="btn-primary" href="#courses">Explore Courses</a>
      </div>
    </section>
  );
}
