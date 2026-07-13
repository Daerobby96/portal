<style>
    .stat-card {
        background: white;
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        transition: width 0.3s ease;
    }
    
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }
    
    .stat-card:hover::before {
        width: 6px;
    }
    
    .stat-card-primary::before {
        background: #6366f1;
    }
    
    .stat-card-success::before {
        background: #10b981;
    }
    
    .stat-card-info::before {
        background: #3b82f6;
    }
    
    .stat-card-warning::before {
        background: #f59e0b;
    }
    
    .stat-card-danger::before {
        background: #ef4444;
    }
    
    .stat-card-secondary::before {
        background: #6b7280;
    }
    
    .stat-card-body {
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .stat-icon i {
        font-size: 24px;
    }
    
    .stat-icon-primary {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }
    
    .stat-icon-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .stat-icon-info {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .stat-icon-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .stat-icon-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .stat-icon-secondary {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }
    
    .stat-content {
        flex: 1;
        min-width: 0;
    }
    
    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #111827;
        line-height: 1;
    }
</style>
